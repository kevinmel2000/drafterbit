<?php return [

/**
 *--------------------------------------------------------------------------
 * Service Providers and Each Provided Services
 *--------------------------------------------------------------------------
 *
 * we need to create list of providers along with the provided services.
 * This is really necessarry for lazy loading for each service.
 */


    'Drafterbit\\Core\\Provider\\ThemeServiceProvider' => ['themes'],
    
    'Drafterbit\\Modules\\Finder\\Providers\\OpenFinderServiceProvider' => ['ofinder'],
    
    'Drafterbit\\Modules\\Files\\Providers\\FinderServiceProvider' => ['finder'],
    'Drafterbit\\Modules\\Files\\Providers\\FilesystemServiceProvider' => ['file'],
    'Drafterbit\\Modules\\Support\\Providers\\ImageServiceProvider' => ['image'],
    'Drafterbit\\Modules\\Support\\Providers\\AssetServiceProvider' => ['asset'],
    'Drafterbit\\Modules\\Support\\Providers\\YamlServiceProvider' => ['yaml'],
    'Drafterbit\\Modules\\Support\\Providers\\TwigServiceProvider' => ['twig'],
    
    'Partitur\\Provider\\SwiftMailerServiceProvider' => ['mailer', 'mail'],
    'Partitur\\Provider\\CacheServiceProvider' => ['cache'],
    'Partitur\\Provider\\DatabaseServiceProvider' => ['db'],
    'Partitur\\Provider\\LanguageServiceProvider' => ['lang'],
    'Partitur\\Provider\\TemplateServiceProvider' => ['template'],
    'Partitur\\Provider\\ValidationServiceProvider' => ['validator', 'validation.form'],
];