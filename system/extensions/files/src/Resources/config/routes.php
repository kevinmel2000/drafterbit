<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'files/browser' => ['controller' => '@files\Admin::browser'],
			'files/data' => ['controller' => '@files\Admin::data']
		]
	]
];