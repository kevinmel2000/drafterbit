<?php namespace Drafterbit\Extensions\Admin;

class AdminExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		$this['helper']->register('message', $this->getResourcesPath('helpers/message.php'));
		$this['helper']->register('admin', $this->getResourcesPath('helpers/admin.php'));
		$this['helper']->load('message');
		$this['helper']->load('admin');
	}
}