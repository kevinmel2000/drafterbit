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
    
    'Drafterbit\\Extensions\\System\\Providers\\ImageServiceProvider' => ['image'],
    'Drafterbit\\Extensions\\System\\Providers\\AssetServiceProvider' => ['asset'],
    'Drafterbit\\Extensions\\System\\Providers\\YamlServiceProvider' => ['yaml'],
    'Drafterbit\\Extensions\\System\\Providers\\TwigServiceProvider' => ['twig'],
];