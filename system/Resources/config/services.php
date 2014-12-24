<?php return [

/**
 *--------------------------------------------------------------------------
 * Service Providers and Each Provided Services
 *--------------------------------------------------------------------------
 *
 * we need to create list of providers along with the provided services.
 * This is really necessarry for lazy loading for each service.
 */
    'Drafterbit\\System\\Provider\\ImageServiceProvider' => ['image'],
    'Drafterbit\\System\\Provider\\TwigServiceProvider' => ['twig'],
    'Drafterbit\\System\\Provider\\ThemeServiceProvider' => ['themes'],
	'Drafterbit\\System\\Provider\\MailServiceProvider' => ['mailer', 'mail'],
];