<?php namespace Drafterbit\Widgets\Text;

use Drafterbit\CMS\Widget\Widget;

class TextWidget extends Widget {

	public function run($data = null)
	{
		return $data['content'];
	}
}