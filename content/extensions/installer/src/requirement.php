<?php return [
    [
        'name' => "PHP Version",
        'function' => function() {
            return version_compare($ver = PHP_VERSION, $req = '5.4.0', '>='); 
        },
        'message' => 'You need to run PHP 5.4+ to get drafterbit running'
    ],
    [
        'name' => 'cache Directory',
        'function' => function($app) {
            return is_writable($app['path.content'].'cache'); 
        },
        'message' => "Content cache directory is required to be writable"
    ]
];