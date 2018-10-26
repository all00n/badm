<?php

return [
    'user' =>[
        'controller'=> 'UserController',
        'patch'      => 'controller/UserController.php',
        'permission' => 'administrator',
        'methods'    => [
            'GET'   =>  ['action' => 'getUser', 'slug' => '/^[0-9]{1,10}$/'],
            'POST'  =>  ['action' => 'addUser'],
            'PUT'   =>  ['action' => 'editUser','slug' => '/^[0-9]{1,10}$/'],
        ]
    ],

    'login' =>[
        'controller'=> 'LoginController',
        'patch'     => 'controller/LoginController.php',
        'permission' => '',
        'methods'    => [
            'POST'  =>  ['action' => 'login'],
        ]
    ],

    'fibonacci' => [
        'controller'=> 'FibonacciController',
        'patch'      => 'controller/FibonacciController.php',
        'permission' => 'user',
        'methods'    => [
            'GET'   =>  ['action' => 'fibonacci', 'slug' => '/^[0-9]{1,10}$/']
        ]
    ],

    'get_price' => [
        'controller'=> 'PriceController',
        'patch'      => 'controller/PriceController.php',
        'permission' => 'user',
        'methods'    => [
            'GET'   =>  ['action' => 'getPrice', 'slug' => '/^(\d+(\.\d{1,2})?)$/']
        ]
    ],

    'tasks' => [
        'controller'=> 'TaskController',
        'patch'      => 'controller/TaskController.php',
        'permission' => 'user',
        'methods'    => [
            'GET'   =>  ['action' => 'getTasks']
        ]
    ],
    'task' => [
        'controller'=> 'TaskController',
        'patch'      => 'controller/TaskController.php',
        'permission' => 'user',
        'methods'    => [
            'GET'   =>  ['action' => 'getTask', 'slug' => '/^[0-9]{1,10}$/'],
            'POST'  =>  ['action' => 'addTask'],
        ]
    ],

    'addComment' => [
        'controller'=> 'TaskController',
        'patch'      => 'controller/TaskController.php',
        'permission' => 'user',
        'methods'    => [
            'POST'   =>  ['action' => 'addComment', 'slug' => '/^[0-9]{1,10}$/']
        ]
    ],
];