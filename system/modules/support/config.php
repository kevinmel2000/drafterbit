<?php return [
	'name' => 'support',
	'menus' => [
		['id'=>'system', 'label' => 'System'],
		['id'=>'content', 'label' => 'Content'],
		['id'=>'users', 'label' => 'Users'],
		['id'=>'setting', 'label' => 'Setting'],
		['id'=>'help', 'label' => 'Help'],

		['id'=>'dashboard', 'label' => 'Dashboard', 'href' => '/', 'parent' => 'system'],

		['id'=>'doc.wiki', 'parent'=>'help', 'label' => 'Doc wiki', 'href' => '#', 'class'=> 'soon'],
		['id'=>'support', 'parent'=>'help', 'label' => 'Official Support', 'href' => '#', 'class'=> 'soon']
	]
];