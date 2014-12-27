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
		'post.view' => 'view post',
		'post.edit' => 'edit post',
		'post.save' => 'save a post',
		'post.delete' => 'delete or trash post',
		'comment.view' => 'view comment',
		'comment.delete' => 'delete comment',
	]
];