<?php return [
	
	'name' => 'blog',
	'ns' => 'Drafterbit\\',
	'autoload' => [
		'psr-4' => [
			'Drafterbit\\Blog\\' => 'src'
		]
	],
	'menus' => [
		[ 'id' => 'blog', 'parent' => 'content', 'label' => 'Blog', 'href' => 'blog'],
	],
	'permissions' => [
		'blog.view' => 'view blog',
		'blog.add' => 'add blog',
		'blog.edit' => 'edit blog',
		'blog.delete' => 'delete blog'
	]
];