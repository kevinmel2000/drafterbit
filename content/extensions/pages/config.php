<?php return [

    'name' => 'pages',
    'nav' => [
            [ 'id'=>'pages', 'parent' =>'content', 'label' => 'Pages', 'href' => 'pages', 'order' => 1],
    ],
    'permissions' => [
        'page.view' => 'view page',
        'page.add' => 'add page',
        'page.edit' => 'edit page',
        'page.delete' => 'delete page',
    ]
];
