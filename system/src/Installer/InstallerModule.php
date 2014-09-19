<?php namespace Drafterbit\CMS\Installer;

use Drafterbit\Framework\Module;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class InstallerModule extends Module {

	/**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app['exception']->error(function(NotFoundHttpException $e) {
            return redirect(base_url('installer'))->send();
        });
    }
}