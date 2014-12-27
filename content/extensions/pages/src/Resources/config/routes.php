<?php return [
	
	'%admin%' =>[
		'subRoutes' => [
			'pages' => [
				'subRoutes' => [
					'/' 				 => ['controller' => '@pages\Pages::index',  'access' => 'page.view'],
					'edit/{id}' 		 => ['controller' => '@pages\Pages::edit',   'access' => 'page.view'],
					'data/{status}.json' => ['controller' => '@pages\Pages::filter', 'access' => 'page.view'],
					'save' 				 => ['controller' => '@pages\Pages::save',   'access' => 'page.edit',   'csrf' => true],
					'trash' 			 => ['controller' => '@pages\Pages::trash',  'access' => 'page.delete', 'csrf' => true]
				]
			]
		]
	]
];