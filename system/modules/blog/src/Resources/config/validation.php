<?php return [

'blog' => [
			'title' 	=> array('label' => 'Title', 	'rules' => array('not-empty') ),
			'content' 	=> array('label' => 'Content', 	'rules' => array('optional') ),
			'slug' 		=> array('label' => 'Slug', 	'rules' => array('not-empty', 'alpha-dash') ),
			'tags' 		=> array('label' => 'Tags', 	'rules' => array('optional') )
]

];