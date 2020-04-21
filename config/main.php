<?php
    $app = [
        'site' => [
            'title' => 'Sociable'
        ],
        'database' => [
            'host' => 'localhost',
            'name' => 'sociable',
            'user' => 'sociable',
            'password' => '1111'
        ],
        'app' => [
            'default_action' => 'MainController@index' 
        ]
    ];

    return $app;