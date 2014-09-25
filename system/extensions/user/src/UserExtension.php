<?php namespace Drafterbit\Extensions\User;

class UserExtension extends \Drafterbit\Framework\Extension {

	public function createTables()
	{
		$schema = $this['db']->getSchemaManager()->createSchema();		
		
		// user
		$users = $schema->createTable('#_users');
		$users->addColumn('id', 'integer', ['autoincrement' => true]);
		$users->addColumn('email', 'string', ['length' => 150]);
		$users->addColumn('password', 'string', ['length' => 60]);
		$users->addColumn('real_name', 'string', ['length' => 45]);
		$users->addColumn('website', 'string', ['length' => 45]);
		$users->addColumn('bio', 'string', ['length' => 501]);
		$users->addColumn('log_token', 'string', ['length' => 45]);
		$users->addColumn('pw_reset_code', 'string', ['length' => 45]);
		$users->addColumn('pw_reset_time', 'integer');
		$users->addColumn('created_at', 'datetime');
		$users->addColumn('updated_at', 'datetime');
		$users->addColumn('deleted_at', 'datetime');
		$users->addColumn('status', 'boolean');
		$users->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($users);

		//group
		$groups = $schema->createTable('#_groups');
		$groups->addColumn('id', 'integer', ['autoincrement' => true]);
		$groups->addColumn('label', 'string', ['length' => 45]);
		$groups->addColumn('description', 'string', ['length' => 255]);
		$groups->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($groups);

		// users_groups
		$users_groups = $schema->createTable('#_users_groups');
		$users_groups->addColumn('id', 'integer',['autoincrement' => true]);
		$users_groups->addColumn('user_id', 'integer');
		$users_groups->addColumn('group_id', 'integer');
		$users_groups->setPrimaryKey(['id']);
		$users_groups->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$users_groups->addForeignKeyConstraint('#_groups', ['group_id'], ['id']);
		$this['db']->getSchemaManager()->createTable($users_groups);

		//permissions
		$permissions = $schema->createTable('#_permissions');
		$permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$permissions->addColumn('slug', 'string', ['length' => 45]);
		$permissions->addColumn('label', 'string', ['length' => 255]);
		$permissions->addColumn('extension', 'string', ['length' => 45]);
		$permissions->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($permissions);

		//group _pemissions
		$groups_permissions = $schema->createTable('#_groups_permissions');
		$groups_permissions->addColumn('id', 'integer', ['autoincrement' => true]);
		$groups_permissions->addColumn('group_id', 'integer');
		$groups_permissions->addColumn('permission_id', 'integer');
		$groups_permissions->setPrimaryKey(['id']);
		$groups_permissions->addForeignKeyConstraint('#_groups', ['group_id'], ['id']);
		$groups_permissions->addForeignKeyConstraint('#_permissions', ['permission_id'], ['id']);
		$this['db']->getSchemaManager()->createTable($groups_permissions);
	}
}