<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'files' => ['controller' => '@files\Files::index', 'access' => 'files.view'],
			'files/browser' => ['controller' => '@files\Files::browser', 'access' => 'files.view'],
			'files/data' => ['controller' => '@files\Files::data', 'csrf' => true]
		]
	]
];