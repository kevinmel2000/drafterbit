<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Application;

class SystemExtension extends \Drafterbit\Framework\Extension {

    function boot()
    {
        foreach (['form', 'support', 'twig'] as $helper) {
            $this['helper']->register( $helper, $this->getResourcesPath("helpers/$helper.php"));
            $this['helper']->load($helper);
        }
    }

    function getReservedBaseUrl()
    {
        return ['search'];
    }

    function dashboardWidgets()
    {
        return array(
            $this->model('@system\Dashboard')->recent(),
            $this->model('@system\Dashboard')->info()
        );
    }
}