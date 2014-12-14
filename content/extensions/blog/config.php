<?php return [
	
	'name' => 'blog',
	'ns' => 'Drafterbit\\',
	'autoload' => [
		'psr-4' => [
			'Drafterbit\\Blog\\' => 'src'
		]
	],
	'menus' => [
		[ 'id' => 'blog', 'label' => 'Blog', 'href' => 'blog', 'parent' => 'content'],
		[ 'id' => 'comments', 'label' => 'Comments', 'href' => 'blog/comments', 'order' => 2]
	],
	'permissions' => [
		'blog.view' => 'view post',
		'blog.edit' => 'edit post',
		'blog.save' => 'save a post',
		'blog.delete' => 'delete post'
	]
];