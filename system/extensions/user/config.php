<?php return [
	'name' => 'user',
	'menus' => [
		['parent'=>'users', 'id'=>'user', 'label' => 'User', 'href' => 'user/index'],
		['parent'=>'users', 'id'=>'group', 'label' => 'Group', 'href' => 'user/group']
	],
	'permissions' => [
		'user.view' => 'view user',
		'user.add' => 'add user',
		'user.edit' => 'edit user',
		'user.delete' => 'delete user',

		'usergroup.view' => 'view user group',
		'usergroup.add' => 'add user group',
		'usergroup.edit' => 'edit user group',
		'usergroup.delete' => 'delete user group'
	]
];