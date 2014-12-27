<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'files'         => ['controller' => '@files\Files::index', 'access' => 'files.manage'],
			'files/browser' => ['controller' => '@files\Files::browser'],
			'files/data'    => ['controller' => '@files\Files::data', 'csrf' => true]
		]
	]
];