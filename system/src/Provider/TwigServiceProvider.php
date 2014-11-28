<?php namespace Drafterbit\System\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TwigServiceProvider implements ServiceProviderInterface {

    public function register(Container $app)
    {
        $app['twig.options'] = array();
        $app['twig.templates'] = array();

        $app['twig'] = function ($app) {
            $app['twig.options'] = array_replace(
                array(
                    'autoescape'       => false,
                    'charset'          => $app['config']['app.charset'],
                    'debug'            => $app['config']['app.debug'],
                    'strict_variables' => $app['config']['app.debug']
                ), $app['twig.options']
            );

            $twig = new \Twig_Environment($app['twig.loader'], $app['twig.options']);
            $twig->addGlobal('app', $app);

            $globalVar = [
                'csrfToken' => csrf_token()
            ];

            foreach ($globalVar as $key => $val) {
                $twig->addGlobal($key, $val);
            }

            //add widget function
            $function = new \Twig_SimpleFunction('widget', function($p){
                return widget($p);
            });

            $baseUrl = new \Twig_SimpleFunction('base_url', function($p = null){
                return base_url($p);
            });

            $themeUrl = new \Twig_SimpleFunction('theme_url', function($p = null){
                return theme_url($p);
            });

            $twig->addFunction($baseUrl);
            $twig->addFunction($themeUrl);
            $twig->addFunction($function);

            if ($app['config']['app.debug']) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }
            
            return $twig;
        };

        $app['twig.loader.filesystem'] = function ($app) {
            return new \Twig_Loader_Filesystem($app['path.theme']);
        };

        $app['twig.loader.array'] = function ($app) {
            return new \Twig_Loader_Array($app['twig.templates']);
        };

        $app['twig.loader'] = function ($app) {
            return new \Twig_Loader_Chain(array(
                $app['twig.loader.array'],
                $app['twig.loader.filesystem'],
            ));
        };
    }
}
