<?php namespace Drafterbit\Widgets\Text;

use Drafterbit\Component\Widget\Widget;

class TextWidget extends Widget {

	public function run($data = null)
	{
		return $data['content'];
	}
}