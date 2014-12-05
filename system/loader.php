<?php

$loader = require __DIR__.'/vendor/autoload.php';
$loader->addPsr4('Drafterbit\\System\\', __DIR__.'/src');

return $loader;