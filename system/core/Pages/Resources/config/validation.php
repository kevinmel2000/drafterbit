<?php return [
	'page' => [
				'title' 	=> array('label' => 'Title', 	'rules' => array('not-empty') ),
				'content' 	=> array('label' => 'Content', 	'rules' => array('optional') ),
				'slug' 		=> array('label' => 'Slug', 	'rules' => array('not-empty', 'alpha-dash') ),
				'template' 	=> array('label' => 'Tags', 	'rules' => array('not-empty') )
	]
];