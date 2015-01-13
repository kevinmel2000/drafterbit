<?php return [

/**
 * ---------------------------------------------------------------------------
 * Administration Base Path
 * ---------------------------------------------------------------------------
 *
 * This is base path whare you access administration panel from address bar.
 * exemple: if you set this to 'admin' then you can access the admin panel
 * at 'http://domain.tld/admin'.
 */
'path.admin' =>  'admin',

/**
 * ---------------------------------------------------------------------------
 * Uploaded Files Dir
 * ---------------------------------------------------------------------------
 *
 * This is folder where all uploaded files will be stored. Feel free to
 * change it but note that the value must be writable and relative to
 * installation folder.
 *
 * WARNING ! if you change this, you also need to update your embeded image.
 */
'path.upload' =>  '%content_dir%/files',

/**
 * ---------------------------------------------------------------------------
 * Database Connection Config
 * ---------------------------------------------------------------------------
 *
 * This is database connection detail you need to provide to connect the
 * application to database. Driver that supported is only 'pdo_mysql' for now.
 */

'database' => [
    'driver'       => 'pdo_mysql',
    'user'         => 'root',
    'password'     => '',
    'host'         => 'localhost',
    'port'         => '3306',
    'dbname'       => '',
    'charset'      => 'utf8',
    'collation'    => 'utf8_unicode_ci',
    'prefix'       => 'dt_',
    ]
];