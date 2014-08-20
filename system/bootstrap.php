<?php

$loader = require './system/vendor/autoload.php';

$app = new Partitur\Application;

// Drafterbit use composer loader to as module loader
$app->set('loader', $loader);

$app->set('path.install', __DIR__ . '/../');
$app->set('path.public', __DIR__ . '/../');
$app->set('path.cache', $app->get('user_config')->get('config.path.cache').'/data');

//Partitur Modules
foreach ([

/**
 *
 *--------------------------------------------------------------------------
 * Modules
 *--------------------------------------------------------------------------
 *
 * Partitur uses 'Module' term to build the application. Each module provides
 * several controller as well as services that can be used on the app.
 */
    'Drafterbit\\Support\\Module',
    'Drafterbit\\User\\Module',
    'Drafterbit\\Admin\\Module',
    'Drafterbit\\Pages\\Module',
    'Drafterbit\\Files\\Module',
    'Drafterbit\\System\\Module',
    //'Egig\\OpenFinder\\Module'

] as $module) {
	$app->registerModule(new $module($app));
}

$pubModulesPath = $app->get('config')->get('drafterbit.modules.path');
$pubModules = $app->get('finder')->in($pubModulesPath)->directories()->depth(0);

foreach ($pubModules as $mod) {

	$module = $mod->getFileName();
	$modConfig = require $pubModulesPath.$module.'/module.php';

	// register autoload
	foreach ($modConfig['autoload'] as $key => $value) {
		switch($key) {
			case 'psr-4':
				foreach ($value as $ns => $path) {
					$app->get('loader')->addPsr4($ns, $pubModulesPath.$module.'/'.$path);
				}
			break;

			case 'psr-0':
				foreach ($value as $ns => $path) {
					$app->get('loader')->addNamespace($ns, $pubModulesPath.$module.'/'.$path);
				}
			break;

			case 'classmap':
					$app->get('loader')->addClassmap($value);
			break;

			case 'files':
				foreach ($value as $file) {
					require $pubModulesPath.$module.'/'.$file;
				}
			break;
		}
	}

	//register modules
	$class = $modConfig['ns'].'Module';

	$mod = new $class($app);
	$app->registerModule($mod);
}

return $app;