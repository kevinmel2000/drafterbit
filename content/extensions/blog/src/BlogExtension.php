<?php namespace Drafterbit\Blog;

use Drafterbit\Framework\ExtensionEvent;
use Drafterbit\Framework\Application;

class BlogExtension extends \Drafterbit\Framework\Extension {

	public function boot()
	{
		$this['helper']->register('blog', $this->getResourcesPath('helpers/blog.php'));
		$this['helper']->load('blog');

		$ns = $this->getNamespace();
		$extensionClass = $ns.'\\Extensions\\TwigExtension';

		// this must be after path.theme registered
		if(class_exists($extensionClass)) {
			$this['twig']->addExtension( new $extensionClass);
		}

		$this->getApplication()->addFrontPageOption(['blog' => [
			'label' => 'Blog',
			'controller' => '@blog\Blog::index',
			'defaults' => array()
			]
		]);

	}

	public function getComments($id)
	{
		$model = $this->model('@blog\Comment');

		$comments = $model->getByPostId($id);

		return $comments;
	}

	public function createTables()
	{
		$schema = $this['db']->getSchemaManager()->createSchema();		
		
		// pages
		$posts = $schema->createTable('#_posts');
		$posts->addColumn('id', 'integer',['autoincrement' => true]);
		$posts->addColumn('slug', 'string', ['length' => 150]);
		$posts->addColumn('title', 'string', ['length' => 150]);
		$posts->addColumn('content', 'text');
		$posts->addColumn('user_id', 'integer');
		$posts->addColumn('created_at', 'datetime');
		$posts->addColumn('updated_at', 'datetime');
		$posts->addColumn('deleted_at', 'datetime');
		$posts->addColumn('status', 'boolean');
		$posts->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$posts->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($posts);

		// tags
		$tags = $schema->createTable('#_tags');
		$tags->addColumn('id', 'integer',['autoincrement' => true]);
		$tags->addColumn('slug', 'string', ['length' => 45]);
		$tags->addColumn('label', 'string', ['length' => 45]);
		$tags->setPrimaryKey(['id']);
		$this['db']->getSchemaManager()->createTable($tags);

		//posts_tags
		$posts_tags = $schema->createTable('#_posts_tags');
		$posts_tags->addColumn('id', 'integer', ['autoincrement' => true]);
		$posts_tags->addColumn('post_id', 'integer');
		$posts_tags->addColumn('tag_id', 'integer');
		$posts_tags->setPrimaryKey(['id']);
		$posts_tags->addForeignKeyConstraint('#_posts', ['post_id'], ['id']);
		$posts_tags->addForeignKeyConstraint('#_tags', ['tag_id'], ['id']);
		$this['db']->getSchemaManager()->createTable($posts_tags);

		//comments
		$comments = $schema->createTable('#_comments');
		$comments->addColumn('id', 'integer', ['autoincrement' => true]);
		$comments->addColumn('content', 'text');
		$comments->addColumn('post_id', 'integer');
		$comments->addColumn('user_id', 'integer');
		$comments->addColumn('name', 'string', ['length' => 45, 'notnull' => true]);
		$comments->addColumn('email', 'string', ['length' => 150, 'notnull' => true]);
		$comments->addColumn('website', 'string', ['length' => 150]);
		$comments->addColumn('created_at', 'datetime');
		$comments->addColumn('updated_at', 'datetime');
		$comments->addColumn('deleted_at', 'datetime');
		$comments->addColumn('status', 'boolean');
		$comments->setPrimaryKey(['id']);
		$comments->addForeignKeyConstraint('#_posts', ['post_id'], ['id']);
		$comments->addForeignKeyConstraint('#_users', ['user_id'], ['id']);
		$this['db']->getSchemaManager()->createTable($comments);
	}

	function getSearchQuery()
	{
		$query = $this['db']->createQueryBuilder()
			->select('*')
			->from('#_posts', 'p')
			->where("p.title like :q")
			->orWhere("p.content like :q");

		return array('blog', $query);
	}
}