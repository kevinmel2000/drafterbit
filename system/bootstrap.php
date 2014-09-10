<?php defined('ENVIRONMENT') or die();

$loader = require __DIR__.'/vendor/autoload.php';

use Drafterbit\CMS\CMSBase;

class Application extends CMSBase {}

$app = new Application(ENVIRONMENT);

$app['loader'] = $loader;

return $app;