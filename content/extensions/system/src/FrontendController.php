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
		return $this->get('twig')->render($template, $data);
	}
}