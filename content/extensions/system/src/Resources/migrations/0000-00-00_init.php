<?php return [
    
    'up' => function() use ($app)
    {
        $schema = $app['db']->getSchemaManager()->createSchema();        
        
        // system
        $system = $schema->createTable('#_system');
        $system->addColumn('id', 'integer', ['autoincrement' => true]);
        $system->addColumn('name', 'string', ['length' => 45]);
        $system->addColumn('value', 'text');
        $system->setPrimaryKey(['id']);
        $app['db']->getSchemaManager()->createTable($system);

        // log
        $logs = $schema->createTable('#_logs');
        $logs->addColumn('id', 'integer', ['autoincrement' => true]);
        $logs->addColumn('channel', 'string', ['length' => 45]);
        $logs->addColumn('level', 'integer');
        $logs->addColumn('message', 'text');
        $logs->addColumn('time', 'integer');
        $logs->addColumn('context', 'text');
        $logs->setPrimaryKey(['id']);
        $app['db']->getSchemaManager()->createTable($logs);

        // menus
        $menus = $schema->createTable('#_menus');
        $menus->addColumn('id', 'integer', ['autoincrement' => true]);
        $menus->addColumn('label', 'string', ['length' => 150]);
        $menus->addColumn('page', 'string', ['length' => 150]);
        $menus->addColumn('link', 'string', ['length' => 255]);
        $menus->addColumn('sequence', 'integer');
        $menus->addColumn('type', 'integer');
        $menus->addColumn('position', 'string', ['length' => 45]);
        $menus->addColumn('theme', 'string', ['length' => 45]);
        $menus->addColumn('data', 'text');
        $menus->setPrimaryKey(['id']);
        $app['db']->getSchemaManager()->createTable($menus);

        // widget
        $widgets = $schema->createTable('#_widgets');
        $widgets->addColumn('id', 'integer', ['autoincrement' => true]);
        $widgets->addColumn('name', 'string', ['length' => 45]);
        $widgets->addColumn('title', 'string', ['length' => 150]);
        $widgets->addColumn('sequence', 'integer');
        $widgets->addColumn('position', 'string', ['length' => 45]);
        $widgets->addColumn('theme', 'string', ['length' => 45]);
        $widgets->addColumn('data', 'text');
        $widgets->setPrimaryKey(['id']);
        $app['db']->getSchemaManager()->createTable($widgets);
    },

    'down' => function()
    {

    }
];