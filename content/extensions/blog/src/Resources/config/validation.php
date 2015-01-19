<?php return [

'blog' => [
    'title'    => array('label' => 'Title',     'rules' => array('not-empty') ),
    'content'  => array('label' => 'Content',     'rules' => array('optional') ),
    'slug'     => array('label' => 'Slug',     'rules' => array('not-empty', 'alpha-dash') ),
    'tags'     => array('label' => 'Tags',     'rules' => array('optional') )
],

'comment' => [
    'name'      => ['label' => 'Name', 'rules' => ['not-empty']],
    'email'     => ['label' => 'Email', 'rules' => ['not-empty','email']],
    'url'       => ['label' => 'URL', 'rules' => ['optional']],
    'content'   => ['label' => 'Comment', 'rules' => ['optional']],
    'parent_id' => ['label' => '', 'rules' => ['not-empty']],
    'post_id'   => ['label' => '', 'rules' => ['not-empty']]
]

];
