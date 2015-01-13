<?php namespace Drafterbit\System\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

class DrafterbitSystemExtension extends Twig_Extension
{

    function getName()
    {
        return 'drafterbit_system';
    }

    function getFunctions()
    {
        return array(

            new \Twig_SimpleFunction('widget', function($p){
                return widget($p);
            }),

            new \Twig_SimpleFunction('menus', function($p){
                return menus($p);
            }),

            new \Twig_SimpleFunction('base_url', function($p = null){
                return base_url($p);
            }),

            new \Twig_SimpleFunction('theme_url', function($p = null){
                return theme_url($p);
            }),

            new \Twig_SimpleFunction('blog_url', function($p = null){
                return blog_url($p);
            }),

            new \Twig_SimpleFunction('__', function($id, array $parameters = array(), $domain = null, $locale = null){
                return __($id, $parameters, $domain, $locale);
            })
        );
    }

    function getGlobals()
    {
        return array(
            'app' => app(),
            'csrfToken' => csrf_token()
        );
    }
}