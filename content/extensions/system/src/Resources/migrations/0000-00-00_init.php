<?php return [
	
	'up' => function ($app)
	{
		$schema = $app['db']->getSchemaManager()->createSchema();		
		
		// system
		$system = $schema->createTable('#_system');
		$system->addColumn('id', 'integer',['autoincrement' => true]);
		$system->addColumn('name', 'string',['length' => 45]);
		$system->addColumn('value', 'text');
		$system->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($system);

		// log
		$logs = $schema->createTable('#_logs');
		$logs->addColumn('id', 'integer',['autoincrement' => true]);
		$logs->addColumn('channel', 'string',['length' => 45]);
		$logs->addColumn('level', 'integer');
		$logs->addColumn('message', 'text');
		$logs->addColumn('time', 'integer');
		$logs->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($logs);

		//widget
		$widgets = $schema->createTable('#_widgets');
		$widgets->addColumn('id', 'integer',['autoincrement' => true]);
		$widgets->addColumn('name', 'string',['length' => 45]);
		$widgets->addColumn('title', 'string',['length' => 150]);
		$widgets->addColumn('position', 'string',['length' => 45]);
		$widgets->addColumn('theme', 'string',['length' => 45]);
		$widgets->addColumn('data', 'text');
		$widgets->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($widgets);
	},

	'down' => function()
	{

	}
];