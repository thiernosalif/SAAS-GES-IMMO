<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixBienZones extends Command
{
    protected $signature = 'db:fix-zones';
    protected $description = 'Fix zone_id on biens: set zone_id=2 (Ziguinchor) where adresse contains Ziguinchor-area keywords';

    // Keywords identifying Ziguinchor-area addresses (matched case-insensitively)
    private const ZIG_KEYWORDS = [
        'ZIGUINCHOR', 'ZIG', 'TILENE', 'TILÈNE', 'KANDÉ', 'KANDE',
        'LYNDIANE', 'BOUCOTTE', 'DIEFAYE', 'KANDIALANG', 'COLOBANE',
        'KENIA', 'DIABIR', 'GOUMEL', 'PERYSSAC', 'BINTEGNE',
    ];

    public function handle(): int
    {
        $total   = DB::table('biens')->count();
        $before  = DB::table('biens')->where('zone_id', 2)->count();

        $this->info("Biens total: {$total} | Already zone_id=2 before fix: {$before}");

        $updated = 0;

        foreach (self::ZIG_KEYWORDS as $kw) {
            $rows = DB::table('biens')
                ->where('zone_id', '!=', 2)
                ->whereRaw('LOWER(adresse) LIKE ?', ['%' . strtolower($kw) . '%'])
                ->update(['zone_id' => 2]);

            if ($rows > 0) {
                $this->line("  Keyword <comment>{$kw}</comment>: {$rows} bien(s) updated");
                $updated += $rows;
            }
        }

        $after = DB::table('biens')->where('zone_id', 2)->count();

        $this->info("Done. {$updated} bien(s) switched to zone_id=2. Total zone_id=2 now: {$after}");

        return self::SUCCESS;
    }
}
