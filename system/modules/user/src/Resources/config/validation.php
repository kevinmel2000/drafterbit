<?php return [

'login' => [
	'email' => [
		'label' => 'Email',
		'rules' => ['not-empty', 'email']
	],

	'password' => [
		'label' => 'Password',
		'rules' => ['not-empty']
	]
],

'user' => [
	'real-name' => array('label' => 'Real Name', 	'rules' => array('optional')),
	'website' 	=> array('label' => 'Website', 		'rules' => array('optional')),
	'bio' 		=> array('label' => 'Bio', 			'rules' => array('optional', 'max-length=500')),
	'groups' 	=> array('label' => 'Group', 		'rules' => array('not-empty')),
	'email' 	=> array('label' => 'Email', 		'rules' => array('not-empty', 'email')),
	'password' 	=> array('label' => 'Password', 	'rules' => array('optional')),
	'password-confirm' 	=> array('label' => 'Password Confirmation', 'rules' => array('optional', 'match=password')),
],

'group' => [
	'name' => array('label' => 'Group Name', 'rules' => array('not-empty')),
	'description' => array('label' => 'description', 'rules' => array('optional', 'max-length=500'))
]


];