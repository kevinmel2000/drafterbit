<?php define('ENVIRONMENT', 'dev');

$content = __DIR__.'/content';
$system = __DIR__.'/system/';

$app = require $system.'bootstrap.php';

$app->run();