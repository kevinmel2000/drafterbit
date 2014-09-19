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
'path.admin' =>  'backend',

/**
 * ---------------------------------------------------------------------------
 * Cache Folder
 * ---------------------------------------------------------------------------
 *
 * This is folder where all application cache will be stored. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */
'path.cache' =>  'content/cache',

/**
 * ---------------------------------------------------------------------------
 * Themes Install Dir
 * ---------------------------------------------------------------------------
 *
 * This is folder where all themes are installed. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */
'path.theme' => 'content/themes',

/**
 * ---------------------------------------------------------------------------
 * Widget install Dir
 * ---------------------------------------------------------------------------
 *
 * This is folder where all widget are installed. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */
'path.widget' =>  'content/widget',

/**
 * ---------------------------------------------------------------------------
 * Uploaded Files Dir
 * ---------------------------------------------------------------------------
 *
 * This is folder where all uploaded files will be stored. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */
'path.upload' =>  'content/files',

/**
 * ---------------------------------------------------------------------------
 * Modules Install Dir
 * ---------------------------------------------------------------------------
 *
 * This is folder where all modules will be installed. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */
'path.modules' =>  'content/modules',

/**
 * ---------------------------------------------------------------------------
 * Database Connection Config
 * ---------------------------------------------------------------------------
 *
 * This is folder where all modules will be installed. Feel free to
 * change it but note that the value must be writable and reletive to
 * installation folder.
 */

'database' => [
	'driver'       => 'pdo_mysql',
    'user'         => 'root',
    'password'     => '',
    'host'         => 'localhost',
    'port'         => '3306',
    'dbname'       => 'drafterbit',
    'charset'      => 'utf8',
    'collation'    => 'utf8_unicode_ci',
    'prefix'       => 'dt_',
	]
];
