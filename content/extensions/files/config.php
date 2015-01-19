<?php return [
    'name' => 'files',
    'nav' => [
            [ 'id'=>'files', 'parent' =>'content', 'label' => 'Files', 'href' => 'files', 'order' => 2],
    ],
    'permissions' => [
        'files.manage' => 'manage files',
    ]
];
