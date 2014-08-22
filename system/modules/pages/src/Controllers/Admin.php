<?php namespace Drafterbit\Modules\Pages\Controllers;

use Drafterbit\Modules\Admin\BaseController;
use Drafterbit\Modules\User\Models\Auth;
use Drafterbit\Modules\Pages\Models\Page as PageModel;
use Models\Post;
use Carbon\Carbon;

class Admin extends BaseController {
	
	protected $page;

	public function __construct(Auth $auth, PageModel $page)
	{
		parent::__construct($auth);
		$this->page = $page;
	}

	public function index()
	{
		$this->auth->restrict('page.view');

		if($post = $this->get('input')->post('pages')) {
			$this->_handleIndexRequest($post['page'], $post['action']);
		}

		// get data
		$cache = $this->get('cache');
		if( ! $cache->contains('pages') ) {
			$cache->save('pages', $this->page->all());
		}
		$pages = $cache->fetch('pages');


		// prepare asset
		$this->get('asset')
		->css('@bootstrap_datatables_css')
		->js('@datatables_js')
		->js('@bootstrap_datatables_js')
		->js('@jquery_check_all')
		->js($this->assetPath('js/admin-index.js'));

		$ui = $this->model('UI@admin');
		$header 	= $ui->header('Pages', 'Pages');
		$table 		= $ui->datatables('page', $pages, $this->_table());
		$toolbar 	= $ui->toolbar($this->_toolbarIndex());
		$listFormed = $ui->listFormed(null, $toolbar, $table);
		$content 	= $header.$listFormed;

		return $this->wrap($content);
	}

	private function _handleIndexRequest($postIds, $action)
	{
		switch($action) {
			case "Delete":
				foreach ($pageIds as $id) {
					$this->page->delete($id);
				}
				message('posts deleted !', 'success');
				break;
			default:
				break;
		}
	}

	private function _handleEditRequest($postData, $id)
	{
		try {
			$this->validate('page', $postData);
			$data = $this->createUpdateData($postData);
			$this->page->update($data, $id);
			message('Post succesfully updated.', 'success');

		} catch (\Exception $e) {
			message($e->getMessage(), 'error');
		}
	}

	private function _toolbarIndex()
	{
		return array(
			'trash' => array(
				'type' => 'submit',
				'label' => 'Trash',
				'name'=> 'action',
				'value' => 'trash',
				'faClass' => 'fa-trash-o'
			),
			'new-post' => array(
				'type' => 'a.success',
				'href' => base_url('admin/pages/create'),
				'label' => 'New Page',
				'faClass' => 'fa-plus'
			),
		);
	}

	private function _toolbarCreate()
	{
		return array(
			'cancel' => array(
				'type' => 'a.default',
				'href' => base_url('admin/blog'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
			'save-draft' => array(
				'type' => 'submit.default',
				'label' => 'Save Draft',
				'name'=> 'action',
				'value' => 'draft',
				'faClass' => false
			),
			'publish' => array(
				'type' => 'submit.success',
				'label' => 'Publish',
				'name'=> 'action',
				'value' => 'publish',
				'faClass' => 'fa-check'
			),
		);
	}

	private function _toolbarEdit()
	{
		return array(
			'cancel' => array(
				'type' => 'a.default',
				'href' => base_url('admin/pages'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
			'update' => array(
				'type' => 'submit.success',
				'label' => 'Update',
				'name'=> 'action',
				'value' => 'update',
				'faClass' => 'fa-check'
			),
		);
	}

	private function _table()
	{
		$editUrl = base_url('admin/pages/edit');

		return  array(
			[
				'field' => 'title',
				'label' => 'Title',
				'width' => '80%',
				'format' => function($value, $item) use ($editUrl) { return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; }],
			[
				'field' => 'created_at',
				'label' => 'Date',
				'width' => '20%']
		);
	}

	public function create()
	{
		$this->auth->restrict('page.add');
		
		if ($postData = $this->get('input')->post()) {

			try {
				$this->validate('page', $postData);
				$data = $this->createInsertData($postData);
				$id = $this->page->insert($data);

				message('Post succesfully saved.', 'success');

			} catch (\Exception $e) {
				message($e->getMessage(), 'error');
			}
		}

		set(array(
			'id' => null,
			'title' => null,
			'slug' => null,
			'content' => null,
			'template' => null
		));
		
		$this->get('asset')
			->css($this->assetPath('css/editor.css'))
			->js($this->assetPath('js/editor.js'));

		$ui = $this->model('UI@admin');
		$header 	= $ui->header('New Page', 'Create new static page');
		$toolbar 	= $ui->toolbar($this->_toolbarCreate());
		$view 		= $this->render('admin/edit', $this->getData());
		$form 		= $ui->form(null, $toolbar, $view);		
		$content 	= $header.$form;

		return $this->wrap($content);
	}

	public function edit($id)
	{
		$this->auth->restrict('page.edit');
		
		if ($post = $this->get('input')->post() ) {
			$this->_handleEditRequest($post, $id);
		}

		$page = $this->page->getSingleBy('id', $id);

		set(array(
			'id' => $id,
			'title' => $page->title,
			'slug' => $page->slug,
			'content' => $page->content,
			'template' => $page->template
		));

		$this->get('asset')
		->css($this->assetPath('css/editor.css'))
		->js($this->assetPath('js/editor.js'));

		$ui = $this->model('UI@admin');
		$header 	= $ui->header('Edit Page', 'Edit page');
		$toolbar 	= $ui->toolbar($this->_toolbarEdit());
		$view 		= $this->render('admin/edit', $this->getData());
		$form 		= $ui->form(null, $toolbar, $view);		
		$content 	= $header.$form;

		return $this->wrap($content);
	}

	/**
	 * Parse post data to insert to db
	 *
	 * @param array $post
	 * @return array
	 */
	protected function createInsertData($post, $isUpdate = false)
	{
		$data = array();
		$data['title'] = $post['title'];
		$data['slug'] = $post['slug'];
		$data['content'] = $post['content'];
		$data['template'] = $post['template'];
		$data['user_id'] = $this->get('session')->get('uid');
		$data['updated_at'] = Carbon::now();
		
		if (! $isUpdate) {
			$data['created_at'] = Carbon::now();
		}

		return $data;
	}

	/**
	 * Parse post data for update
	 *
	 * @param array $post
	 * @return array
	 */
	public function createUpdateData($post)
	{
		return $this->createInsertData($post, true);
	}

	public function dashboard()
	{
		set('postCount', count($this->post->all()));
		set('pageCount', count($this->page->all()));
		return view();
	}

	public function preferences()
	{
		return view();
	}
}