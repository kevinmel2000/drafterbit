<?php return [

/**
 *--------------------------------------------------------------------------
 * Application Debug Mode
 *--------------------------------------------------------------------------
 *
 * When your application is in debug mode detailed error messages with
 * stack traces will be shown on every error that occurs within your
 * application. If disabled a simple error page is shown.
 */

'debug' => true,

/**
 *--------------------------------------------------------------------------
 * Application Timezone
 *--------------------------------------------------------------------------
 *
 * Here you may specify the default timezone for your application which will
 * be used by the PHP date and time functions. You probably want to check
 * supported timzone: http://id1.php.net/timezones
 */

'timezone' => 'UTC',

/**
 *--------------------------------------------------------------------------
 * Application Character Set
 *--------------------------------------------------------------------------
 *
 * It's useful for response generation and many case. Default is already
 * defined for you for convenient.
 */

'charset' => 'UTF-8',

/**
 *--------------------------------------------------------------------------
 * Application Language
 *--------------------------------------------------------------------------
 *
 * This will be used for translation in validation and others. We use simple
 * language support provided by Partitur. If you need more,  you can always
 * add you own language library.
 */

'language' => 'english',

/**
 *--------------------------------------------------------------------------
 * Application Encryption Key
 *--------------------------------------------------------------------------
 *
 * This key is required for encryption handling in cookie and session, you
 * need to set it to long, hard-to-guess, unpredictable string, unless you
 * don't care about security.
 */

'key' => 'encryption-key',

/**
 *--------------------------------------------------------------------------
 * Application Start Script
 *--------------------------------------------------------------------------
 *
 * In case that we need to override things we can do it in this file. Start
 * script run after boot just before handling the request.
 */

'startscript' => '',

/**
 *--------------------------------------------------------------------------
 * Aplication Log
 *--------------------------------------------------------------------------
 *
 * Wether you want log the application error or not. If you in development
 * mode, you probably want shut it down by set following value to false.
 * Log will be recorded per day in 'app/logs' directory.
 */

'error.log' => false,

/**
 *--------------------------------------------------------------------------
 * Service Providers and Each Provided Services
 *--------------------------------------------------------------------------
 *
 * we need to create list of providers along with the provided services.
 * This is really necessarry for lazy loading for each service.
 */

'services' => [

    'Egig\\OpenFinder\\Providers\\OpenFinderServiceProvider' => ['ofinder'],
    
    'Drafterbit\\Files\\Providers\\FilesystemServiceProvider' => ['file'],
    'Drafterbit\\Files\\Providers\\FinderServiceProvider' => ['finder'],
    'Drafterbit\\Support\\Providers\\UserConfigServiceProvider' => ['user_config'],
    'Drafterbit\\Support\\Providers\\ImageServiceProvider' => ['image'],
    'Drafterbit\\Support\\Providers\\AssetServiceProvider' => ['asset'],
    'Drafterbit\\Support\\Providers\\YamlServiceProvider' => ['yaml'],
    'Drafterbit\\Support\\Providers\\TwigServiceProvider' => ['twig'],
    
    'Partitur\\Provider\\SwiftMailerServiceProvider' => ['mailer', 'mail'],
    'Partitur\\Provider\\CacheServiceProvider' => ['cache'],
    'Partitur\\Provider\\DatabaseServiceProvider' => ['db'],
    'Partitur\\Provider\\LanguageServiceProvider' => ['lang'],
    'Partitur\\Provider\\TemplateServiceProvider' => ['template'],
    'Partitur\\Provider\\ValidationServiceProvider' => ['validator', 'validation.form'],
 ]

];