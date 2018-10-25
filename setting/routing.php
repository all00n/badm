<?php

$routes = [
    'user' =>[
        'controller'=> 'UserController',
        'patch'      => 'controller/UserController.php',
        'methods'    => [
            'GET'   =>  ['action' => 'getUser', 'slug' => '/^[0-9]{1,10}$/'],
            'POST'  =>  ['action' => 'addUser'],
            'PUT'   =>  ['action' => 'editUser','slug' => '/^[0-9]{1,10}$/'],
        ]
    ],

    'login' =>[
        'controller'=> 'LoginController',
        'patch'     => 'controller/LoginController.php',
        'methods'    => [
            'POST'  =>  ['action' => 'login'],
        ]
    ],

    'fibonacci' => [
        'controller'=> 'FibonacciController',
        'patch'      => 'controller/FibonacciController.php',
        'methods'    => [
            'GET'   =>  ['action' => 'fibonacci', 'slug' => '/^[0-9]{1,10}$/']
        ]
    ],

    'string' => [
        'controller'=> 'StringController',
        'patch'      => 'controller/StringController.php',
        'methods'    => [
            'GET'   =>  ['action' => 'number2string', 'slug' => '/^(\d+(\.\d{1,2})?)$/']
        ]
    ],
    
    'home' =>[
      'controller'=> 'HomeController',
      'patch'      => 'controller/HomeController.php',
      'action'    => 'index',
      'reg'       =>'/^[a-zA-Z]{1,10}$/'
    ],

    'page' =>[
      'controller'=> 'PageController',
      'patch'      => 'controller/PageController.php',
      'action'    => 'index',
      'slug'      => [
          'reg' => '/^[0-9]{1,10}$/',
          'default' => 1
      ]
    ],
    
    'pages' =>[
      'controller'=> 'PageController',
      'patch'      => 'controller/PageController.php',
      'action'    => 'getPages'
    ]
];