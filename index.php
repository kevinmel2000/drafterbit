<?php define('ENVIRONMENT', 'dev');

$app = require './system/bootstrap.php';

$app['path.install'] = $app['path.public'] =  __DIR__ .'/';

$app->run();