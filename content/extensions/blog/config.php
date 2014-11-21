<?php return [
	
	'name' => 'blog',
	'ns' => 'Drafterbit\\',
	'autoload' => [
		'psr-4' => [
			'Drafterbit\\Blog\\' => 'src'
		]
	],
	'menus' => [
		[ 'id' => 'blog', 'label' => 'Blog', 'href' => 'blog/index', 'parent' => 'content'],
		[ 'id' => 'comments', 'label' => 'Comments', 'href' => 'blog/comments', 'order' => 2]
	],
	'permissions' => [
		'blog.view' => 'view blog',
		'blog.add' => 'add blog',
		'blog.edit' => 'edit blog',
		'blog.delete' => 'delete blog'
	]
];