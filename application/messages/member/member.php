<?php

return array(
    'email' => array(
        'not_empty' => 'Email could not be empty',
        'min_length' => 'Minimum length of email are 4 characters',
        'max_length' => 'Maximum length of email are 100 characters',
        'email' => 'Please specify valid email',
        'isUniqueEmail' => 'Such email is already exist',
    ),
    'name' => array(
        'not_empty' => 'Name could not be empty',
        'min_length' => 'Minimum length of name are 2 characters',
        'max_length' => 'Maximum length of name are 50 characters',
        'alpha_dash' => 'Name should contain only alphanumerical, hyphen and undescore characters',
    ),
    'surname' => array(
        'not_empty' => 'Surname could not be empty',
        'min_length' => 'Minimum length of surname are 2 characters',
        'max_length' => 'Maximum length of surname are 50 characters',
        'alpha_dash' => 'Surname should contain only alphanumerical, hyphen and undescore characters',
    ),
    'personal_code' => array(
        'not_empty' => 'Personal code could not be empty',
        'exact_length' => 'Personal code length should be exactly 32 characters',
        'alpha_numeric' => 'Personal code should contain only alphabetical with hyphens',
    ),
    'address' => array(
        'max_length' => 'Maximum length of address are 100 characters',
        'regex' => 'Address should contain only alphanumerical, underscore, hyphen, point or comma characters',
    ),
    'country' => array(
        'not_empty' => 'Country could not be empty',
        'max_length' => 'Maximum length of country are 30 characters',
        'alpha' => 'Country should contain only alphabetical characters',
    ),
    'city' => array(
        'not_empty' => 'City could not be empty',
        'max_length' => 'Maximum length of city are 30 characters',
        'alpha' => 'City should contain only alphabetical characters',
    ),
);
