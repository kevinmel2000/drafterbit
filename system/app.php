<?php defined('ENVIRONMENT') or die();

file_exists($autoloadFile  = __DIR__.'/vendor/autoload.php')
    or die('Composer autoload file not found, run `composer install` ?');

$loader = require $autoloadFile;
$loader->addPsr4('Drafterbit\\System\\', __DIR__.'/src');

class App extends Drafterbit\System\Kernel {}

$app = new App(ENVIRONMENT);
$app['loader'] = $loader;

return $app;