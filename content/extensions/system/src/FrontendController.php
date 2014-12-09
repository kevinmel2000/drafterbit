<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Controller;

class FrontendController extends Controller {

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
		if($this->get('session')->get('customize_mode')) {

			echo 'Customize Mode is On';
			echo $this->get('input')->get('customizing');
			
			$customData = $this->get('session')->get('customize_data');
			$globals = array(
				'siteName' => isset($customData['siteName']) ? $customData['siteName'] : null,
				'siteDesc' => isset($customData['siteDesc']) ? $customData['siteDesc'] : null
				);

		} else {
			$system = $this->model('@system\System')->all();
			$globals = array(
				'siteName' =>  $system['site.name'],
				'siteDesc' => $system['site.description']
			);
		}

		foreach ($globals as $key => $value) {

			$this->get('twig')->addGlobal($key, $value);
		}

		return $this->get('twig')->render($template, $data);
	}
}