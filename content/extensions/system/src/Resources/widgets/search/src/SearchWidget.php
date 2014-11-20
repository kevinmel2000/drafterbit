<?php namespace Drafterbit\Widgets\Search;

use Drafterbit\System\Widget\Widget;

class SearchWidget extends Widget {

	public function run()
	{
		return '<form action="'.base_url('search').'"><input type ="text" name="q">
		<input  type="submit" value="Search">
		</form>';
	}
}