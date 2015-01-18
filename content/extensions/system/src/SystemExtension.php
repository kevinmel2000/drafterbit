<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Application;

class SystemExtension extends \Drafterbit\Framework\Extension {

    public function boot()
    {
        foreach (['form', 'support', 'twig'] as $helper) {
            $this['helper']->register( $helper, $this->getResourcesPath("helpers/$helper.php"));
            $this['helper']->load($helper);
        }
    }

    public function getReservedBaseUrl()
    {
        return ['search'];
    }

    public function dashboardWidgets()
    {
        return array(
            'recent' => $this->model('@system\Dashboard')->recent(),
            'stat' => $this->model('@system\Dashboard')->info()
        );
    }
}