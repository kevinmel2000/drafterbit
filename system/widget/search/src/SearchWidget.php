<?php namespace Drafterbit\Widgets\Search;

use Drafterbit\Core\Widget;

class SearchWidget extends Widget {

	public function run($data = null)
	{
		return $data['content'];
	}
}