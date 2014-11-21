<?php return [
	'name' => 'user',
	'menus' => [
		['parent'=>'users', 'id'=>'user', 'label' => 'User', 'href' => 'user/index'],
		['parent'=>'users', 'id'=>'roles', 'label' => 'Roles', 'href' => 'user/roles']
	],
	'permissions' => [
		'user.view' => 'view user',
		'user.add' => 'add user',
		'user.edit' => 'edit user',
		'user.delete' => 'delete user',

		'roles.view' => 'view roles',
		'roles.add' => 'add roles',
		'roles.edit' => 'edit roles',
		'roles.delete' => 'delete roles'
	]
];