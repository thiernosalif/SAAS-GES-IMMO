<?php

return [
    'titre'    => 'Paiements',
    'ajouter'  => 'Enregistrer un paiement',
    'detail'   => 'Détail du paiement',
    'recu'     => 'Reçu de paiement',
    'recu_numero' => 'Reçu N° :numero',

    // Champs
    'locataire'             => 'Locataire',
    'contrat'               => 'Contrat',
    'periode'               => 'Période',
    'montant'               => 'Montant',
    'montant_du'            => 'Montant dû',
    'mode_paiement'         => 'Mode de règlement',
    'avance'                => 'Avance de loyer',
    'acompte'               => 'Acompte',
    'complement'            => 'Complément',
    'transaction_reference' => 'Référence transaction',
    'statut'                => 'Statut',
    'encaisse_par'          => 'Encaissé par',
    'date'                  => 'Date',

    // Statuts
    'statuts' => [
        'complet' => 'Complet',
        'partiel' => 'Partiel',
        'avance'  => 'Avance',
    ],

    // Modes de règlement
    'modes' => [
        'especes'      => 'Espèces',
        'virement'     => 'Virement bancaire',
        'mobile_money' => 'Mobile Money',
        'cheque'       => 'Chèque',
    ],

    // Messages
    'ajouter_succes' => 'Paiement enregistré avec succès.',
    'aucun_paiement' => 'Aucun paiement enregistré.',
];
