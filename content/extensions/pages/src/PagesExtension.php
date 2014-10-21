<?php namespace Drafterbit\Extensions\Pages;

class PagesExtension extends \Drafterbit\Framework\Extension {

	public function boot()
	{

	}

	public function createTables()
	{
		$schema = $this['db']->getSchemaManager()->createSchema();		
		
		// pages
		$pages = $schema->createTable('#_pages');
		$pages->addColumn('id', 'integer',['autoincrement' => true]);
		$pages->addColumn('slug', 'string', ['length' => 150]);
		$pages->addColumn('title', 'string', ['length' => 150]);
		$pages->addColumn('content', 'text');
		$pages->addColumn('user_id', 'integer');
		$pages->addColumn('created_at', 'datetime');
		$pages->addColumn('updated_at', 'datetime');
		$pages->addColumn('deleted_at', 'datetime');
		$pages->addColumn('layout', 'string', ['length' => 45, 'default' => 'default.html']);
		$pages->addColumn('status', 'boolean');
		$pages->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$pages->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($pages);
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
}