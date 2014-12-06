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

		$system = $this->model('@system\System')->all();

		$this->get('twig')->addGlobal('siteName', $system['site.name']);
		$this->get('twig')->addGlobal('siteDesc', $system['site.description']);

		return $this->get('twig')->render($template, $data);
	}
}