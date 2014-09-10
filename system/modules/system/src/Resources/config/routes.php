<?php return [

ADMIN_BASE => [
	'group' => [
		'system' => [
			'group' => [
				'log' => ['controller' => '@system\Admin::log'],
				'cache' => ['controller' => '@system\Admin::cache']
			],
		],
	],
],

];