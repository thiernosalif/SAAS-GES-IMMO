<?php

if (!function_exists('format_fcfa')) {
    function format_fcfa(float $montant): string
    {
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }
}

if (!function_exists('mois_francais')) {
    function mois_francais(int $mois): string
    {
        $mois_fr = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
        ];
        return $mois_fr[$mois] ?? '';
    }
}

if (!function_exists('periode_label')) {
    function periode_label(string|\DateTimeInterface $date): string
    {
        $d = $date instanceof \DateTimeInterface
            ? $date
            : \Carbon\Carbon::parse($date);
        return mois_francais((int) $d->format('n')) . ' ' . $d->format('Y');
    }
}

if (!function_exists('numero_recu')) {
    function numero_recu(int $id, int $annee): string
    {
        return 'REC-' . $annee . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}
