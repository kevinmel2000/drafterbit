<?php return [

    '%admin%' => [
        'before'    => '@user\Models\Auth::authenticate',
        'methods'   => 'get|post',
        'subRoutes' => [
            '/'         => ['controller' => '@system\System::dashboard'],
            'login'     => ['controller' => '@user\Auth::login'],
            'do_login'  => ['controller' => '@user\Auth::doLogin', 'methods' => 'post', 'log.after' => ['type' => 1, 'message' => '@user:%user_id% logged in']],
            'logout'    => ['controller' => '@user\Auth::logout', 'log.before' => ['type' => 1, 'message' => '@user:%user_id% logged out']],
            'setting'   => [
                'subRoutes' => [
                    'general'   => ['controller' => '@system\Setting::general', 'access' => 'system.change'],
                    'themes'    => [
                        'subRoutes' => [
                            '/'              => ['controller' => '@system\Theme::index', 'access' => 'appearance.change'],
                            'customize'      => ['controller' => '@system\Theme::customize',     'csrf'=>true],
                            'custom-preview' => ['controller' => '@system\Theme::customPreview', 'csrf'=>true],
                            
                            'widget'         => ['controller' => '@system\Widget::index'],
                            'widget/save'    => ['controller' => '@system\Widget::save', 'methods' => 'post'],
                            'widget/delete'  => ['controller' => '@system\Widget::delete'],
                            'widget/sort'    => ['controller' => '@system\Widget::sort'],

                            'menus'          => ['controller' => '@system\Menus::index'],
                            'menus/save'     => ['controller' => '@system\Menus::save', 'methods' => 'post'],
                            'menus/delete'   => ['controller' => '@system\Menus::delete'],
                            'menus/sort'     => ['controller' => '@system\Menus::sort'],
                        ]
                    ]
                ]
            ],

            'system' => [
                'subRoutes' => [
                    'dashboard'     => ['controller' => '@system\System::dashboard'],
                    'log'           => ['controller' => '@system\System::log',  'access' => 'log.view'],
                    'cache'         => ['controller' => '@system\System::cache','access' => 'cache.view'],
                ],
            ],
        ],
    ],

    'system/drafterbit.js' => ['controller' => '@system\System::drafterbitJs'],
    'system/drafterbit.css' => ['controller' => '@system\System::drafterbitCss'],

    'search' => [
        'controller' => '@system\Frontend::search'
    ],

    // Trail-slash Redirector
    '{url}' => [
        'controller' => function($url){
            return redirect(base_url(rtrim($url, '/')), 301);
        },
        'requirements' => [
            'url' => ".*/$"
        ],
        'methods' => 'get'
    ]
];
