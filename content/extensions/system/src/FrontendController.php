<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Controller;

class FrontendController extends Controller
{

    /**
     * Helper method to render template
     *
     * @return string
     */
    function render($template, $data = array())
    {
        pathinfo($template, PATHINFO_EXTENSION)
            or $template .= '.html';


        // we need to check if site is not being customized
        // if yes we need to use temporary custom data 
        $system = $this->model('@system\System')->all();
        
        if($this->get('session')->get('customize_mode')) {

            $customizingBadge = '<div style="color:#9C6D3B;padding:5px;background:#FCF8E3;z-index:9999;">Customize Mode is On</div>';

            $customData = $this->get('session')->get('customize_data');
            $globals = array(
                'siteName' => isset($customData['siteName']) ? $customData['siteName'] : $system['site.name'],
                'siteDesc' => isset($customData['siteDesc']) ? $customData['siteDesc'] : $system['site.description']
            );

        } else {
            $customizingBadge = '';
            $globals = array(
                'siteName' =>  $system['site.name'],
                'siteDesc' => $system['site.description']
            );
        }

        foreach ($globals as $key => $value) {

            $this->get('twig')->addGlobal($key, $value);
        }

        return $customizingBadge.$this->get('twig')->render($template, $data);
    }
}