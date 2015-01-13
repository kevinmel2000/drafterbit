<?php return [
    'name' => 'system',

    'nav' => [
        ['id' => 'general', 'parent' => 'setting', 'label' => 'General', 'href' => 'setting/general', 'order' => 1],
        ['id' => 'themes', 'parent' => 'setting', 'label' => 'Themes', 'href' => 'setting/themes', 'order' => 2],

        //['id'=>'dashboard', 'label' => 'Dashboard', 'href' => '/'],
        ['id'=>'content', 'label' => 'Content'],
        ['id'=>'users', 'label' => 'Users'],
        ['id'=>'setting', 'label' => 'Setting'],
        ['id'=>'system', 'label' => 'System'],

        ['parent'=>'system', 'id'=> 'log', 'label' => 'Log', 'href' => 'system/log'],
        ['parent'=>'system', 'id'=> 'cache', 'label' => 'Cache', 'href' => 'system/cache'],

        // help coming soon
        // ['id'=>'help', 'label' => 'Help'],
        // ['id'=>'doc.wiki', 'parent'=>'help', 'label' => 'Documentation Wiki', 'href' => '#', 'class'=> 'soon'],
        // ['id'=>'community', 'parent'=>'help', 'label' => 'Community Forum', 'href' => '#', 'class'=> 'soon'],
        // ['id'=>'support', 'parent'=>'help', 'label' => 'Official Support', 'href' => '#', 'class'=> 'soon']
    
    ],

    'permissions' => [
        'system.change' => 'change system setting',
        'appearance.change' => 'change appearance setting',
        'log.view' => 'view log',
        'cache.view' => 'view cache'
    ]
];