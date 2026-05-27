<?php

return [
    'titre'    => 'Payments',
    'ajouter'  => 'Record a payment',
    'detail'   => 'Payment details',
    'recu'     => 'Payment receipt',
    'recu_numero' => 'Receipt No. :numero',

    'locataire'             => 'Tenant',
    'contrat'               => 'Lease',
    'periode'               => 'Period',
    'montant'               => 'Amount',
    'montant_du'            => 'Amount due',
    'mode_paiement'         => 'Payment method',
    'avance'                => 'Rent advance',
    'acompte'               => 'Partial payment',
    'complement'            => 'Remaining balance',
    'transaction_reference' => 'Transaction reference',
    'statut'                => 'Status',
    'encaisse_par'          => 'Collected by',
    'date'                  => 'Date',

    'statuts' => [
        'complet' => 'Complete',
        'partiel' => 'Partial',
        'avance'  => 'Advance',
    ],

    'modes' => [
        'especes'      => 'Cash',
        'virement'     => 'Bank transfer',
        'mobile_money' => 'Mobile Money',
        'cheque'       => 'Cheque',
    ],

    'ajouter_succes' => 'Payment recorded successfully.',
    'aucun_paiement' => 'No payments recorded.',
];
