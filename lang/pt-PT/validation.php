<?php

return [
    'required'  => 'O campo :attribute é obrigatório.',
    'email'     => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'unique'    => 'Este valor já está em uso.',
    'confirmed' => 'A confirmação não corresponde.',
    'numeric'   => 'O campo :attribute deve ser um número.',
    'integer'   => 'O campo :attribute deve ser um número inteiro.',
    'date'      => 'O campo :attribute deve ser uma data válida.',
    'in'        => 'O valor selecionado para :attribute é inválido.',
    'exists'    => 'O valor selecionado para :attribute não existe.',
    'password'  => 'A palavra-passe está incorreta.',

    'min' => [
        'string'  => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
    ],
    'max' => [
        'string'  => 'O campo :attribute não pode ter mais de :max caracteres.',
        'numeric' => 'O campo :attribute não pode ser superior a :max.',
    ],

    'attributes' => [
        'nom'             => 'apelido',
        'prenom'          => 'nome próprio',
        'email'           => 'endereço de e-mail',
        'password'        => 'palavra-passe',
        'telephone'       => 'telefone',
        'montant'         => 'valor',
        'periode'         => 'período',
        'loyer_mensuel'   => 'renda mensal',
        'date_debut'      => 'data de início',
        'date_fin'        => 'data de fim',
        'bien_id'         => 'imóvel',
        'locataire_id'    => 'inquilino',
        'proprietaire_id' => 'proprietário',
        'pays'            => 'país',
        'locale'          => 'idioma',
    ],
];
