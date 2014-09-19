<?php return [

/**
 *--------------------------------------------------------------------------
 * Service Providers and Each Provided Services
 *--------------------------------------------------------------------------
 *
 * we need to create list of providers along with the provided services.
 * This is really necessarry for lazy loading for each service.
 */


    'Drafterbit\\CMS\\Provider\\DatabaseServiceProvider' => ['db'],
    'Drafterbit\\CMS\\Provider\\ThemeServiceProvider' => ['themes'],
    
    
    'Drafterbit\\CMS\\System\\Providers\\ImageServiceProvider' => ['image'],
    'Drafterbit\\CMS\\System\\Providers\\AssetServiceProvider' => ['asset'],
    'Drafterbit\\CMS\\System\\Providers\\YamlServiceProvider' => ['yaml'],
    'Drafterbit\\CMS\\System\\Providers\\TwigServiceProvider' => ['twig'],
];