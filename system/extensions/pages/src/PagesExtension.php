<?php namespace Drafterbit\Extensions\Pages;

class PagesExtension extends \Drafterbit\Framework\Extension {

	public function boot()
	{
		//$this['dispatcher']->addListener('boot', function(){
			$app = $this->GetApplication();
			$app->frontpage = array_merge($app->frontpage, $app->frontpage());

			$system = $this['cache']->fetch('system');

			$homepage = $system['homepage'];

			if(strpos($homepage, 'pages') !==  FALSE) {
				$this['router']->addRouteDefinition('/', ['controller' => '@pages\Pages::home']);
				$id = substr($homepage, -2, -1);
				$this['homepage.id'] = $id;
			} else {
				// @todo add non-page homepage
			}
		//});
	}
}