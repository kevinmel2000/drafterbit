<?php return [

/**
 *--------------------------------------------------------------------------
 * Service Providers and Each Provided Services
 *--------------------------------------------------------------------------
 *
 * we need to create list of providers along with the provided services.
 * This is really necessarry for lazy loading for each service.
 */


    'Drafterbit\\CMS\\Provider\\ThemeServiceProvider' => ['themes'],
    
    'Drafterbit\\Modules\\Finder\\Providers\\OpenFinderServiceProvider' => ['ofinder'],
    
    'Drafterbit\\Modules\\Files\\Providers\\FinderServiceProvider' => ['finder'],
    'Drafterbit\\Modules\\Files\\Providers\\FilesystemServiceProvider' => ['file'],
    'Drafterbit\\Modules\\Support\\Providers\\ImageServiceProvider' => ['image'],
    'Drafterbit\\Modules\\Support\\Providers\\AssetServiceProvider' => ['asset'],
    'Drafterbit\\Modules\\Support\\Providers\\YamlServiceProvider' => ['yaml'],
    'Drafterbit\\Modules\\Support\\Providers\\TwigServiceProvider' => ['twig'],
    
    'Drafterbit\\Framework\\Provider\\SwiftMailerServiceProvider' => ['mailer', 'mail'],
    'Drafterbit\\Framework\\Provider\\CacheServiceProvider' => ['cache'],
    'Drafterbit\\Framework\\Provider\\DatabaseServiceProvider' => ['db'],
    'Drafterbit\\Framework\\Provider\\LanguageServiceProvider' => ['lang'],
    'Drafterbit\\Framework\\Provider\\TemplateServiceProvider' => ['template'],
    'Drafterbit\\Framework\\Provider\\ValidationServiceProvider' => ['validator', 'validation.form'],
];