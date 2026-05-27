<?php

return [
    'required'  => 'Le champ :attribute est obligatoire.',
    'email'     => 'Le champ :attribute doit être une adresse e-mail valide.',
    'unique'    => 'Cette valeur est déjà utilisée.',
    'confirmed' => 'La confirmation ne correspond pas.',
    'numeric'   => 'Le champ :attribute doit être un nombre.',
    'integer'   => 'Le champ :attribute doit être un entier.',
    'date'      => 'Le champ :attribute doit être une date valide.',
    'in'        => 'La valeur sélectionnée pour :attribute est invalide.',
    'exists'    => 'L\'élément sélectionné pour :attribute n\'existe pas.',
    'password'  => 'Le mot de passe est incorrect.',

    'min' => [
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :min.',
    ],
    'max' => [
        'string'  => 'Le champ :attribute ne peut pas dépasser :max caractères.',
        'numeric' => 'Le champ :attribute doit être inférieur ou égal à :max.',
    ],

    'attributes' => [
        'nom'           => 'nom',
        'prenom'        => 'prénom',
        'email'         => 'adresse e-mail',
        'password'      => 'mot de passe',
        'telephone'     => 'téléphone',
        'montant'       => 'montant',
        'periode'       => 'période',
        'loyer_mensuel' => 'loyer mensuel',
        'date_debut'    => 'date de début',
        'date_fin'      => 'date de fin',
        'bien_id'       => 'bien',
        'locataire_id'  => 'locataire',
        'proprietaire_id' => 'propriétaire',
        'pays'          => 'pays',
        'locale'        => 'langue',
    ],
];
