<?php defined('ENVIRONMENT') or die();

$loader = require __DIR__ . '/loader.php';

use Drafterbit\System\Kernel;

class App extends Kernel {}

$app = new App(ENVIRONMENT);
$app['loader'] = $loader;

return $app;