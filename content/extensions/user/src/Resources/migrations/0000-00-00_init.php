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
		$groups = $schema->createTable('#_roles');
		$groups->addColumn('id', 'integer', ['autoincrement' => true]);
		$groups->addColumn('label', 'string', ['length' => 45]);
		$groups->addColumn('description', 'string', ['length' => 255]);
		$groups->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($groups);

		// users_groups
		$users_groups = $schema->createTable('#_users_roles');
		$users_groups->addColumn('id', 'integer',['autoincrement' => true]);
		$users_groups->addColumn('user_id', 'integer');
		$users_groups->addColumn('role_id', 'integer');
		$users_groups->setPrimaryKey(['id']);
		$users_groups->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$users_groups->addForeignKeyConstraint('#_roles', ['group_id'], ['id']);
		$app['db']->getSchemaManager()->createTable($users_groups);

		//permissions
		$permissions = $schema->createTable('#_permissions');
		$permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$permissions->addColumn('slug', 'string', ['length' => 45]);
		$permissions->addColumn('label', 'string', ['length' => 255]);
		$permissions->addColumn('extension', 'string', ['length' => 45]);
		$permissions->setPrimaryKey(['id']);
		$app['db']->getSchemaManager()->createTable($permissions);

		//group _pemissions
		$groups_permissions = $schema->createTable('#_roles_permissions');
		$groups_permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$groups_permissions->addColumn('role_id', 'integer');
		$groups_permissions->addColumn('permission_id', 'integer');
		$groups_permissions->setPrimaryKey(['id']);
		$groups_permissions->addForeignKeyConstraint('#_roles', ['group_id'], ['id']);
		$groups_permissions->addForeignKeyConstraint('#_permissions', ['permission_id'], ['id']);
		$app['db']->getSchemaManager()->createTable($groups_permissions);
	},

	'down' => function()
	{

	}
];