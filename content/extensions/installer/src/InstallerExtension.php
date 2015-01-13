<?php namespace Drafterbit\Extensions\Installer;

use Drafterbit\Framework\Extension;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class InstallerExtension extends Extension {

    protected $start;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this['exception']->error(function(NotFoundHttpException $e) {
            return redirect(base_url('installer'))->send();
        });
    }

    public function setStart($start) {
        $this->start = $start;
    }

    public function getStart() {
        return $this->start;
    }
}