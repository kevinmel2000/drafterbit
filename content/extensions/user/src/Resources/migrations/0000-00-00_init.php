<?php return [
	'up' => function() use($app)
	{
		$schema = $app['db']->getSchemaManager()->createSchema();		
		
		// user
		$users = $schema->createTable('#_users');
		$users->addColumn('id', 'integer', ['autoincrement' => true]);
		$users->addColumn('email', 'string', ['length' => 150]);
		$users->addColumn('password', 'string', ['length' => 60]);
		$users->addColumn('real_name', 'string', ['length' => 45]);
		$users->addColumn('url', 'string', ['length' => 45]);
		$users->addColumn('bio', 'string', ['length' => 501]);
		$users->addColumn('log_token', 'string', ['length' => 45]);
		$users->addColumn('pw_reset_code', 'string', ['length' => 45]);
		$users->addColumn('pw_reset_time', 'integer');
		$users->addColumn('created_at', 'datetime');
		$users->addColumn('updated_at', 'datetime');
		$users->addColumn('deleted_at', 'datetime');
		$users->addColumn('status', 'boolean');
		$users->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($users);

		//group
		$roles = $schema->createTable('#_roles');
		$roles->addColumn('id', 'integer', ['autoincrement' => true]);
		$roles->addColumn('label', 'string', ['length' => 45]);
		$roles->addColumn('description', 'string', ['length' => 255]);
		$roles->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($roles);

		// users_roles
		$users_roles = $schema->createTable('#_users_roles');
		$users_roles->addColumn('id', 'integer',['autoincrement' => true]);
		$users_roles->addColumn('user_id', 'integer');
		$users_roles->addColumn('role_id', 'integer');
		$users_roles->setPrimaryKey(['id']);
		$users_roles->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$users_roles->addForeignKeyConstraint('#_roles', ['role_id'], ['id']);
		$app['db']->getSchemaManager()->createTable($users_roles);

		//permissions
		$permissions = $schema->createTable('#_permissions');
		$permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$permissions->addColumn('slug', 'string', ['length' => 45]);
		$permissions->addColumn('label', 'string', ['length' => 255]);
		$permissions->addColumn('extension', 'string', ['length' => 45]);
		$permissions->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($permissions);

		//group _pemissions
		$roles_permissions = $schema->createTable('#_roles_permissions');
		$roles_permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$roles_permissions->addColumn('role_id', 'integer');
		$roles_permissions->addColumn('permission_id', 'integer');
		$roles_permissions->setPrimaryKey(['id']);
		$roles_permissions->addForeignKeyConstraint('#_roles', ['role_id'], ['id']);
		$roles_permissions->addForeignKeyConstraint('#_permissions', ['permission_id'], ['id']);
		$app['db']->getSchemaManager()->createTable($roles_permissions);
	},

	'down' => function()
	{

	}
];