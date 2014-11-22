<?php return [

'login' => [
	'email' => ['label' => 'Email', 'rules' => ['not-empty', 'email'] ],
	'password' => ['label' => 'Password', 'rules' => ['not-empty'] ]
],

'user' => [
	'real-name' => array('label' => 'Real Name', 	'rules' => array('not-empty')),
	'website' 	=> array('label' => 'Website', 		'rules' => array('optional')),
	'bio' 		=> array('label' => 'Bio', 			'rules' => array('optional', 'max-length=500')),
	'roles' 	=> array('label' => 'Role', 		'rules' => array('not-empty')),
	'email' 	=> array('label' => 'Email', 		'rules' => array('not-empty', 'email')),
	'password' 	=> array('label' => 'Password', 	'rules' => array('optional')),
	'password-confirm' 	=> array('label' => 'Password Confirmation', 'rules' => array('optional', 'match=password')),
],

'roles' => [
	'name' => array('label' => 'Role Name', 'rules' => array('not-empty')),
	'description' => array('label' => 'Description', 'rules' => array('optional', 'max-length=500')),
	'permission' => array('label' => 'Permission', 'rules' => array('optional'))
]

];