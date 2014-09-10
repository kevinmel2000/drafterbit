<?php return [

/**
 *--------------------------------------------------------------------------
 * Default Database Connection Name
 *--------------------------------------------------------------------------
 *
 * Here we specify which of the database connections below we wish
 * to use as default connection. Of course you may use many connections at
 * once using the Database Manager.
 */

'default' => 'mysql',

/**
 *--------------------------------------------------------------------------
 * Database Connections
 *--------------------------------------------------------------------------
 *
 * Here are each of the database connections setup for the application.
 * All database is done through the Doctrine DBAL great foundation. Feel free
 * to append other database config as many as you wish.
 */

'connections' => [

    'sqlite'  => [
        'driver'   => 'pdo_sqlite',
        'user'     => '',
        'passwor'  => '',
        'path'     => '',
        'memory'   => false
    ],

    'mysql'  => [
        'driver'       => 'pdo_mysql',
        'user'         => 'root',
        'password'     => '',
        'host'         => 'localhost',
        'port'         => '3306',
        'dbname'       => '',
        'charset'      => 'utf8',
        'collation'    => 'utf8_unicode_ci',
    ],

    'postgre'  => [
        'driver'   => 'pdo_pgsql',
        'user'     => '',
        'passwor'  => '',
        'host'     => '',
        'port'     => '', 
        'dbname'   => '', 
        'charset'  => '', 
        'sslmode'  => ''
    ],    

    'oracle'  =>[ 
        'driver'       => 'oci8',
        'user'         => '',
        'password'     => '',
        'host'         => '',
        'port'         => '',
        'dbname'       => '', 
        'servicename'  => '',
        'service'      => '',
        'pooled'       => '',
        'charset'      => '',
    ],

    'sqlsrv'  => [
        'driver'   => 'sqlsrv',
        'user'     => '',
        'password' => '',
        'host'     => '',
        'port'     => '',
        'dbname'   => ''
        ],

    'sqlanywhere'  => [
        'driver'       => 'sqlanywhere',
        'user'         => '',
        'password'     => '',
        'server'       => '', 
        'host'         => '',
        'port'         => '',
        'dbname'       => '',
        'persistent'   => true
    ]
]

];