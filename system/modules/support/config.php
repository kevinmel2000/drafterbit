<?php return [
	'name' => 'support',
	'menus' => [
		//['id'=>'dashboard', 'label' => 'Dashboard', 'href' => '/'],
		['id'=>'content', 'label' => 'Content'],
		['id'=>'users', 'label' => 'Users'],
		['id'=>'setting', 'label' => 'Setting'],
		['id'=>'system', 'label' => 'System'],
		['id'=>'help', 'label' => 'Help'],


		['id'=>'doc.wiki', 'parent'=>'help', 'label' => 'Doc wiki', 'href' => '#', 'class'=> 'soon'],
		['id'=>'support', 'parent'=>'help', 'label' => 'Official Support', 'href' => '#', 'class'=> 'soon']
	]
];