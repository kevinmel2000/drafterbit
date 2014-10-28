<?php namespace Drafterbit\Extensions\Pages;

class PagesExtension extends \Drafterbit\Framework\Extension {

	public function boot()
	{

	}

	function getSearchQuery()
	{
		$query = $this['db']->createQueryBuilder()
			->select('*')
			->from('#_pages', 'p')
			->where("p.title like :q")
			->orWhere("p.content like :q");

		return array('page', $query);
	}

	function getShortcuts()
	{
		return [
			['href' => admin_url('pages/edit/new'), 'icon' => 'fa fa-laptop','label' => 'New Page']
		];
	}
}