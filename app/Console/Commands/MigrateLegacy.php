<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLegacy extends Command
{
    protected $signature = 'migrate:legacy
        {--dump-file= : Chemin vers le fichier dump MySQL}
        {--agency-id=1 : ID de l\'agence cible}
        {--fresh : Vider les tables avant migration}
        {--dry-run : Parser sans insérer}';

    protected $description = 'Importe le dump MySQL legacy dans le nouveau schéma multi-tenant';

    private array $userMap     = []; // old_id => new_id
    private array $userZoneMap = []; // old_id => zone_id
    private int   $agencyId;
    private bool  $dryRun;

    public function handle(): int
    {
        $dumpFile       = $this->option('dump-file');
        $this->agencyId = (int) ($this->option('agency-id') ?? 1);
        $this->dryRun   = (bool) $this->option('dry-run');

        if (!$dumpFile || !file_exists($dumpFile)) {
            $this->error('Fichier dump introuvable: ' . ($dumpFile ?? '(non fourni)'));
            return self::FAILURE;
        }

        $this->info("=== Migration Legacy → SGI Immo (agency_id={$this->agencyId}) ===");
        if ($this->dryRun) {
            $this->warn('[dry-run] Aucune donnée ne sera insérée');
        }

        // Load locataires into memory — needed for contrat building (loyer, dates)
        $this->info('Pré-chargement des locataires pour référence...');
        $locataires = [];
        foreach ($this->extractTable($dumpFile, 'locataires') as $row) {
            $locataires[(int) $row['id']] = $row;
        }
        $this->line('  ' . count($locataires) . ' locataires en mémoire');

        if ($this->option('fresh') && !$this->dryRun) {
            $this->freshTables();
        }

        $stats = [];
        $stats['users']         = $this->migrateUsers($this->extractTable($dumpFile, 'users'));
        $stats['proprietaires'] = $this->migrateProprietaires($this->extractTable($dumpFile, 'proprietaires'));
        $stats['biens']         = $this->migrateBiens($this->extractTable($dumpFile, 'biens'));
        $stats['locataires']    = $this->migrateLocataires($locataires);
        $stats['contrats']      = $this->migrateContrats($this->extractTable($dumpFile, 'articles'), $locataires);
        $stats['paiements']     = $this->migratePaiements($this->extractTable($dumpFile, 'reglements'));
        $stats['comptabilites'] = $this->migrateComptabilites($this->extractTable($dumpFile, 'comptabilites'));

        $this->printSummary($stats);

        return self::SUCCESS;
    }

    // ─── Dump parser ──────────────────────────────────────────────────────────

    private function extractTable(string $path, string $tableName): \Generator
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return;
        }

        $active  = false;
        $columns = [];
        $pattern = '/^INSERT INTO `' . preg_quote($tableName, '/') . '` \((.+)\) VALUES$/';

        try {
            while (!feof($handle)) {
                $line = rtrim((string) fgets($handle));

                if (preg_match($pattern, $line, $m)) {
                    $active  = true;
                    $columns = array_map(
                        fn ($c) => trim($c, '` '),
                        explode('`, `', $m[1])
                    );
                } elseif ($active) {
                    if (str_starts_with($line, '(')) {
                        $values = $this->parseValueTuple(rtrim($line, ',;'));
                        if (count($values) === count($columns)) {
                            yield array_combine($columns, $values);
                        }
                    } elseif ($line === '' || str_starts_with($line, '--') || $line === ';') {
                        break;
                    }
                }
            }
        } finally {
            fclose($handle);
        }
    }

    private function parseValueTuple(string $row): array
    {
        $row = trim($row);
        if (str_starts_with($row, '(')) {
            $row = substr($row, 1);
        }
        if (str_ends_with($row, ')')) {
            $row = substr($row, 0, -1);
        }

        $values = [];
        $i      = 0;
        $len    = strlen($row);

        while ($i < $len) {
            // skip space after comma
            while ($i < $len && $row[$i] === ' ') {
                $i++;
            }
            if ($i >= $len) {
                break;
            }

            if (substr($row, $i, 4) === 'NULL') {
                $values[] = null;
                $i += 4;
            } elseif ($row[$i] === "'") {
                $i++;
                $str = '';
                while ($i < $len) {
                    if ($row[$i] === '\\' && $i + 1 < $len) {
                        $str .= match ($row[$i + 1]) {
                            "'"     => "'",
                            'n'     => "\n",
                            'r'     => "\r",
                            't'     => "\t",
                            '\\'    => "\\",
                            default => $row[$i + 1],
                        };
                        $i += 2;
                    } elseif ($row[$i] === "'" && isset($row[$i + 1]) && $row[$i + 1] === "'") {
                        $str .= "'";
                        $i += 2;
                    } elseif ($row[$i] === "'") {
                        $i++;
                        break;
                    } else {
                        $str .= $row[$i++];
                    }
                }
                $values[] = $str;
            } else {
                $start = $i;
                while ($i < $len && $row[$i] !== ',') {
                    $i++;
                }
                $values[] = trim(substr($row, $start, $i - $start));
            }

            if ($i < $len && $row[$i] === ',') {
                $i++;
            }
        }

        return $values;
    }

    // ─── Fresh ────────────────────────────────────────────────────────────────

    private function freshTables(): void
    {
        $this->warn("Nettoyage des tables pour agency_id={$this->agencyId}...");

        $driver = DB::getDriverName();
        $fkOff  = $driver === 'sqlite' ? 'PRAGMA foreign_keys = OFF' : 'SET FOREIGN_KEY_CHECKS=0';
        $fkOn   = $driver === 'sqlite' ? 'PRAGMA foreign_keys = ON'  : 'SET FOREIGN_KEY_CHECKS=1';

        DB::statement($fkOff);

        foreach (['comptabilites', 'paiements', 'contrats', 'biens', 'locataires', 'proprietaires'] as $t) {
            $n = DB::table($t)->where('agency_id', $this->agencyId)->delete();
            $this->line("  {$t}: {$n} supprimés");
        }
        DB::table('users')
            ->where('agency_id', $this->agencyId)
            ->where('role', 'gestionnaire')
            ->delete();

        DB::statement($fkOn);
    }

    // ─── Migrate methods ──────────────────────────────────────────────────────

    private function migrateUsers(iterable $rows): array
    {
        $this->info('→ Utilisateurs...');
        $parsed   = $inserted = 0;
        $zoneById = ['Dakar' => 1, 'Ziguinchor' => 2];

        foreach ($rows as $row) {
            $parsed++;
            $oldId                       = (int) $row['id'];
            $zoneId                      = $zoneById[$row['zone']] ?? 1;
            $this->userZoneMap[$oldId]   = $zoneId;

            if ($this->dryRun) {
                $this->userMap[$oldId] = $oldId + 1000;
                continue;
            }

            $newId                     = DB::table('users')->insertGetId([
                'agency_id'  => $this->agencyId,
                'zone_id'    => $zoneId,
                'nom'        => $row['nom'],
                'prenom'     => $row['prenom'],
                'email'      => $row['email'],
                'password'   => $row['password'],
                'role'       => 'gestionnaire',
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
            $this->userMap[$oldId] = $newId;
            $inserted++;
        }

        return compact('parsed', 'inserted');
    }

    private function migrateProprietaires(iterable $rows): array
    {
        $this->info('→ Propriétaires...');

        return $this->batchInsert('proprietaires', $rows, function (array $row): array {
            return [
                'id'              => (int) $row['id'],
                'agency_id'       => $this->agencyId,
                'cin'             => $row['cin'] ?: null,
                'nom'             => $row['nom'],
                'prenom'          => $row['prenom'],
                'adresse'         => $row['adresse'] ?: null,
                'telephone'       => $row['telephone'] ?: null,
                'email'           => null,
                'date_deb_mandat' => $row['date_deb_mandat'] ?: null,
                'date_fin_mandat' => $row['date_fin_mandat'] ?: null,
                'created_at'      => $row['created_at'],
                'updated_at'      => $row['updated_at'],
            ];
        });
    }

    private function migrateBiens(iterable $rows): array
    {
        $this->info('→ Biens...');

        return $this->batchInsert('biens', $rows, function (array $row): array {
            $userId = (int) $row['users_id'];

            return [
                'id'              => (int) $row['id'],
                'agency_id'       => $this->agencyId,
                'zone_id'         => $this->userZoneMap[$userId] ?? 1,
                'proprietaire_id' => (int) $row['proprietaires_id'],
                'description'     => $this->stripTrial($row['description']),
                'adresse'         => $this->stripTrial($row['adresse']),
                'type'            => $this->normalizeBienType($this->stripTrial($row['type'])),
                'nombre_unites'   => 1,
                'created_at'      => $row['created_at'],
                'updated_at'      => $row['updated_at'],
            ];
        });
    }

    private function migrateLocataires(array $locataires): array
    {
        $this->info('→ Locataires (' . count($locataires) . ')...');

        return $this->batchInsert('locataires', $locataires, function (array $row): array {
            return [
                'id'            => (int) $row['id'],
                'agency_id'     => $this->agencyId,
                'cin'           => $row['cin'] ?: null,
                'nom'           => $row['nom'],
                'prenom'        => $row['prenom'],
                'adresse'       => $row['adresse'] ?: null,
                'telephone'     => $row['telephone'] ?: null,
                'email'         => null,
                'coordonne_pro' => $row['coordonne_pro'] ?: null,
                'mobileref'     => $row['mobileref'] ?? null,
                'created_at'    => $row['created_at'],
                'updated_at'    => $row['updated_at'],
            ];
        }, 300);
    }

    private function migrateContrats(iterable $articles, array $locataires): array
    {
        $this->info('→ Contrats (articles)...');

        return $this->batchInsert('contrats', $articles, function (array $row) use ($locataires): array {
            $locId = (int) $row['locataires_id'];
            $loc   = $locataires[$locId] ?? null;
            $dispo = (int) $row['disponibilite'];

            return [
                'id'                 => (int) $row['id'],
                'agency_id'          => $this->agencyId,
                'bien_id'            => (int) $row['biens_id'],
                'locataire_id'       => $locId,
                'type_logement'      => $row['structure_ar'] ?: null,
                'loyer_mensuel'      => $loc ? (float) $loc['total_loyer'] : 0,
                'charges_mensuelles' => 0,
                'avance_loyer'       => 0,
                'caution'            => 0,
                'date_debut'         => $loc['date_entre'] ?? null,
                'date_fin'           => $loc['expiration_contrat'] ?? null,
                'statut'             => $dispo === 0 ? 'actif' : 'resilie',
                'disponibilite'      => $dispo === 1,
                'created_at'         => $row['created_at'],
                'updated_at'         => $row['updated_at'],
            ];
        }, 300);
    }

    private function migratePaiements(iterable $rows): array
    {
        $this->info('→ Paiements (flux important, patience...)');
        $parsed = $inserted = 0;
        $batch  = [];

        foreach ($rows as $row) {
            $parsed++;

            $moisPaie = $row['mois_paie'];
            $isMonth  = $this->isMonthName($moisPaie);
            $avance     = $row['avance']     !== null ? (float) $row['avance']     : null;
            $acompte    = $row['acompte']    !== null ? (float) $row['acompte']    : null;
            $complement = $row['complement'] !== null ? (float) $row['complement'] : null;

            $statut = 'complet';
            if ($avance !== null && $avance > 0) {
                $statut = 'avance';
            } elseif ($acompte !== null && $acompte > 0) {
                $statut = 'partiel';
            }

            $batch[] = [
                'id'                    => (int) $row['id'],
                'agency_id'             => $this->agencyId,
                'contrat_id'            => (int) $row['articles_id'],
                'locataire_id'          => (int) $row['locataires_id'],
                'periode'               => $this->parsePeriode($moisPaie, $row['created_at']),
                'montant'               => (float) $row['montant'],
                'montant_du'            => (float) $row['montant'],
                'mode_paiement'         => $this->normalizeModeReglement($row['mode_reglement']),
                'avance'                => $avance,
                'acompte'               => $acompte,
                'complement'            => $complement,
                'transaction_reference' => $isMonth ? ($row['transactionreference'] ?: null) : $moisPaie,
                'statut'                => $statut,
                'encaisse_par'          => $this->userMap[(int) $row['users_id']] ?? null,
                'created_at'            => $row['created_at'],
                'updated_at'            => $row['updated_at'],
            ];

            if (count($batch) >= 500) {
                if (!$this->dryRun) {
                    DB::table('paiements')->insertOrIgnore($batch);
                }
                $inserted += count($batch);
                $batch = [];

                if ($parsed % 5000 === 0) {
                    $this->line("  {$parsed} paiements traités...");
                }
            }
        }

        if ($batch) {
            if (!$this->dryRun) {
                DB::table('paiements')->insertOrIgnore($batch);
            }
            $inserted += count($batch);
        }

        return compact('parsed', 'inserted');
    }

    private function migrateComptabilites(iterable $rows): array
    {
        $this->info('→ Comptabilités (flux important, patience...)');
        $parsed = $inserted = 0;
        $batch  = [];

        foreach ($rows as $row) {
            $parsed++;

            $retrait = (float) $row['retrait'];
            $depot   = (float) $row['depot'];

            $batch[] = [
                'id'         => (int) $row['id'],
                'agency_id'  => $this->agencyId,
                'type'       => $retrait > 0 ? 'sortie' : 'entree',
                'montant'    => $retrait > 0 ? $retrait : $depot,
                'motif'      => $row['motif'],
                'categorie'  => null,
                'saisi_par'  => $this->userMap[(int) $row['users_id']] ?? null,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ];

            if (count($batch) >= 500) {
                if (!$this->dryRun) {
                    DB::table('comptabilites')->insertOrIgnore($batch);
                }
                $inserted += count($batch);
                $batch = [];

                if ($parsed % 5000 === 0) {
                    $this->line("  {$parsed} comptabilités traitées...");
                }
            }
        }

        if ($batch) {
            if (!$this->dryRun) {
                DB::table('comptabilites')->insertOrIgnore($batch);
            }
            $inserted += count($batch);
        }

        return compact('parsed', 'inserted');
    }

    // ─── Batch helper ─────────────────────────────────────────────────────────

    private function batchInsert(string $table, iterable $rows, callable $transform, int $size = 200): array
    {
        $parsed = $inserted = 0;
        $batch  = [];

        foreach ($rows as $row) {
            $parsed++;
            $batch[] = $transform($row);

            if (count($batch) >= $size) {
                if (!$this->dryRun) {
                    DB::table($table)->insertOrIgnore($batch);
                }
                $inserted += count($batch);
                $batch = [];
            }
        }

        if ($batch) {
            if (!$this->dryRun) {
                DB::table($table)->insertOrIgnore($batch);
            }
            $inserted += count($batch);
        }

        return compact('parsed', 'inserted');
    }

    // ─── Normalizers & helpers ────────────────────────────────────────────────

    private function isMonthName(string $value): bool
    {
        return in_array($value, [
            'Janvier', 'Fevrier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Aout', 'Septembre', 'Octobre', 'Novembre',
            'Décembre', 'Decembre',
        ], true);
    }

    private function parsePeriode(string $moisPaie, ?string $createdAt): string
    {
        static $months = [
            'Janvier' => 1,  'Fevrier' => 2,   'Février' => 2,  'Mars' => 3,
            'Avril'   => 4,  'Mai'     => 5,   'Juin'    => 6,  'Juillet' => 7,
            'Août'    => 8,  'Aout'    => 8,   'Septembre' => 9, 'Octobre' => 10,
            'Novembre' => 11, 'Décembre' => 12, 'Decembre' => 12,
        ];

        $dt       = $createdAt ? Carbon::parse($createdAt) : Carbon::now();
        $monthNum = $months[$moisPaie] ?? (int) $dt->format('n');

        return sprintf('%04d-%02d-01', $dt->format('Y'), $monthNum);
    }

    private function stripTrial(string $value): string
    {
        // Pattern: NNN-TRIAL-VALUE TRAILING_NNN (Heroku test env artifact)
        if (preg_match('/^\d+-TRIAL-(.+?)\s+\d+\s*$/', $value, $m)) {
            return $m[1];
        }
        // Pattern: TRIAL-VALUE (without leading/trailing numbers)
        if (preg_match('/^TRIAL-(.+)$/', $value, $m)) {
            return trim($m[1]);
        }
        return $value;
    }

    private function normalizeBienType(string $type): string
    {
        return match (strtolower(trim($type))) {
            'maison', 'villa'              => 'villa',
            'appartement', 'appartements' => 'appartement',
            'chambre'                      => 'chambre',
            'studio'                       => 'studio',
            'bureau'                       => 'bureau',
            'magasin'                      => 'magasin',
            'autres', 'autre'              => 'autre',
            default                        => 'autre',
        };
    }

    private function normalizeModeReglement(string $mode): string
    {
        return match (true) {
            stripos($mode, 'cash')     !== false => 'especes',
            stripos($mode, 'mobile')   !== false => 'mobile_money',
            stripos($mode, 'virement') !== false => 'virement',
            stripos($mode, 'bancaire') !== false => 'virement',
            stripos($mode, 'chèque')   !== false => 'cheque',
            stripos($mode, 'cheque')   !== false => 'cheque',
            default                              => 'especes',
        };
    }

    // ─── Summary ──────────────────────────────────────────────────────────────

    private function printSummary(array $stats): void
    {
        $this->newLine();
        $this->table(
            ['Table', 'Parsés', 'Insérés'],
            collect($stats)
                ->map(fn ($v, $k) => [$k, number_format($v['parsed']), number_format($v['inserted'])])
                ->values()
                ->toArray()
        );

        $suffix = $this->dryRun ? ' [dry-run — aucune donnée insérée]' : '';
        $this->info("=== Terminé{$suffix} ===");
    }
}
