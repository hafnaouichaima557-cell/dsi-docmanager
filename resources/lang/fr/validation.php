<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'accepted_if'          => 'Le champ :attribute doit être accepté quand :other est :value.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure à :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale à :date.',
    'alpha'                => 'Le champ :attribute ne doit contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne doit contenir que des lettres, des chiffres, des tirets et des underscores.',
    'alpha_num'            => 'Le champ :attribute ne doit contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure à :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale à :date.',
    'between'              => [
        'array'   => 'Le champ :attribute doit contenir entre :min et :max éléments.',
        'file'    => 'Le champ :attribute doit être compris entre :min et :max kilo-octets.',
        'numeric' => 'Le champ :attribute doit être compris entre :min et :max.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password'     => 'Le mot de passe est incorrect.',
    'date'                 => 'Le champ :attribute n\'est pas une date valide.',
    'date_equals'          => 'Le champ :attribute doit être une date égale à :date.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'email'                => 'Le champ :attribute doit être une adresse email valide.',
    'exists'               => 'Le champ :attribute sélectionné est invalide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute sélectionné est invalide.',
    'integer'              => 'Le champ :attribute doit être un entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne JSON valide.',
    'max'                  => [
        'array'   => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
        'file'    => 'Le champ :attribute ne peut pas être supérieur à :max kilo-octets.',
        'numeric' => 'Le champ :attribute ne peut pas être supérieur à :max.',
        'string'  => 'Le champ :attribute ne peut pas contenir plus de :max caractères.',
    ],
    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                  => [
        'array'   => 'Le champ :attribute doit contenir au moins :min éléments.',
        'file'    => 'Le champ :attribute doit être supérieur à :min kilo-octets.',
        'numeric' => 'Le champ :attribute doit être supérieur à :min.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'password'             => 'Le mot de passe est incorrect.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est obligatoire quand :other est :value.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est :values.',
    'required_with'        => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire quand :values sont présents.',
    'required_without'     => 'Le champ :attribute est obligatoire quand :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire quand aucun de :values n\'est présent.',
    'same'                 => 'Les champs :attribute et :other doivent être identiques.',
    'size'                 => [
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
        'file'    => 'Le champ :attribute doit être de :size kilo-octets.',
        'numeric' => 'Le champ :attribute doit être de :size.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
    ],
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique'               => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded'             => 'Le fichier du champ :attribute n\'a pas pu être téléversé.',
    'url'                  => 'Le format de l\'URL du champ :attribute est invalide.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name'                  => 'nom',
        'email'                 => 'email',
        'password'              => 'mot de passe',
        'password_confirmation'=> 'confirmation du mot de passe',
        'current_password'      => 'mot de passe actuel',
        'photo'                 => 'photo',
        'department'            => 'département',
        'title'                 => 'titre',
        'description'           => 'description',
        'file'                  => 'fichier',
        'status'                => 'statut',
        'priority'              => 'priorité',
        'role'                  => 'rôle',
        'search'                => 'recherche',
        'date'                  => 'date',
        'module'                => 'module',
    ],

];