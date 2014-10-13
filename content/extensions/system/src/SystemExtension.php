<?php namespace Drafterbit\Extensions\System;


use Drafterbit\Framework\Application;

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

		$this['helper']->register('message', $this->getResourcesPath('helpers/message.php'));
		$this['helper']->register('admin', $this->getResourcesPath('helpers/admin.php'));
		$this['helper']->load('message');
		$this['helper']->load('admin');

		//theme
		$config = $this['config'];

		$theme = $this->getTheme();

		$this['path.themes'] = $this['path.content'].'themes/';
		
		$this['themes']->current($theme);
		
		$this['themes']->registerAll();

		$this['path.theme'] = $this['path.themes'].$this['themes']->current().'/';
	}

	private function getTheme()
	{
		$system = $this['cache']->fetch('system');

		return $system['theme'];
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