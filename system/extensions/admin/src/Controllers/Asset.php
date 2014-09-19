<?php namespace Drafterbit\Extensions\Admin\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Drafterbit\Extensions\Support\Controller as BaseController;

class Asset extends BaseController {

	public function css($hash)
	{
		$file = $this->get('asset')->getCachePath().'css/'.$hash;

		return $this->createResponse($file, 'text/css');
	}

	public function js($hash)
	{
		$file = $this->get('asset')->getCachePath().'js/'.$hash;

		return $this->createResponse($file, 'application/javascript');
	}

	protected function createResponse($file, $cType)
	{
		$res = new Response();
		$res->headers->set('Content-Type', $cType);
		$res->setContent(file_get_contents($file));
		return $res;
	}
}