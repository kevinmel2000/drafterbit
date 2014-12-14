<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'files' => ['controller' => '@files\Files::index'],
			'files/browser' => ['controller' => '@files\Files::browser'],
			'files/data' => ['controller' => '@files\Files::data']
		]
	]
];