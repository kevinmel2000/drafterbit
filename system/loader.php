<?php

file_exists($autoloadFile  = __DIR__.'/vendor/autoload.php')
    or die('Composer autoload file not found');

$loader = require $autoloadFile;
$loader->addPsr4('Drafterbit\\System\\', __DIR__.'/src');
$loader->addPsr4('Drafterbit\\', __DIR__.'/vendor/web/finder/server/php');

return $loader;