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
	]
];