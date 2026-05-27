<?php

return [
    'required'  => 'The :attribute field is required.',
    'email'     => 'The :attribute must be a valid email address.',
    'unique'    => 'This value is already in use.',
    'confirmed' => 'The confirmation does not match.',
    'numeric'   => 'The :attribute must be a number.',
    'integer'   => 'The :attribute must be an integer.',
    'date'      => 'The :attribute must be a valid date.',
    'in'        => 'The selected :attribute is invalid.',
    'exists'    => 'The selected :attribute does not exist.',
    'password'  => 'The password is incorrect.',

    'min' => [
        'string'  => 'The :attribute must be at least :min characters.',
        'numeric' => 'The :attribute must be at least :min.',
    ],
    'max' => [
        'string'  => 'The :attribute may not be greater than :max characters.',
        'numeric' => 'The :attribute may not be greater than :max.',
    ],

    'attributes' => [
        'nom'             => 'last name',
        'prenom'          => 'first name',
        'email'           => 'email address',
        'password'        => 'password',
        'telephone'       => 'phone',
        'montant'         => 'amount',
        'periode'         => 'period',
        'loyer_mensuel'   => 'monthly rent',
        'date_debut'      => 'start date',
        'date_fin'        => 'end date',
        'bien_id'         => 'property',
        'locataire_id'    => 'tenant',
        'proprietaire_id' => 'landlord',
        'pays'            => 'country',
        'locale'          => 'language',
    ],
];
