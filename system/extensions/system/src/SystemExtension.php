<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Application;
use Monolog\Logger;
use Drafterbit\Extensions\System\Asset\DrafterbitFontAwesomeFilter as FaFilter;
use Drafterbit\Extensions\System\Asset\DrafterbitChosenFilter as ChosenFilter;

class SystemExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		$this['config']->addReplaces('%path.vendor.asset%', $this['path'].'vendor/web');
		$this['config']->addReplaces('%path.system.asset%', $this['path'].'Resources/public/assets');

		$this['helper']->register('form',$this->getResourcesPath('helpers/form.php'));
		$this['helper']->register('support', $this->getResourcesPath('helpers/support.php'));
		$this['helper']->register('twig', $this->getResourcesPath('helpers/twig.php'));
		
		$this['helper']->load('form');
		$this['helper']->load('support');
		$this['helper']->load('twig');

		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter('system/vendor/web'));
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter('system/vendor/web'));
	}

	public function createTables()
	{
		$schema = $this['db']->getSchemaManager()->createSchema();		
		
		// system
		$system = $schema->createTable('#_system');
		$system->addColumn('id', 'integer',['autoincrement' => true]);
		$system->addColumn('name', 'string',['length' => 45]);
		$system->addColumn('value', 'text');
		$system->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($system);

		// log
		$logs = $schema->createTable('#_logs');
		$logs->addColumn('id', 'integer',['autoincrement' => true]);
		$logs->addColumn('channel', 'string',['length' => 45]);
		$logs->addColumn('level', 'integer');
		$logs->addColumn('message', 'text');
		$logs->addColumn('time', 'integer');
		$logs->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($logs);

		//widget
		$widgets = $schema->createTable('#_widgets');
		$widgets->addColumn('id', 'integer',['autoincrement' => true]);
		$widgets->addColumn('name', 'string',['length' => 45]);
		$widgets->addColumn('title', 'string',['length' => 150]);
		$widgets->addColumn('position', 'string',['length' => 45]);
		$widgets->addColumn('theme', 'string',['length' => 45]);
		$widgets->addColumn('data', 'text');
		$widgets->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($widgets);
	}
}