<?php namespace Drafterbit\Extensions\Pages\Controllers;

use Drafterbit\Extensions\Admin\BaseController;
use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\Pages\Models\Page as PageModel;
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
		// auth restriction
		$this->auth->restrict('page.view');

		if( $post = $this->get('input')->post()) {

			$postIds = $post['pages'];

			switch($post['action']) {
				case "Delete":
					foreach ($pageIds as $id) {
						$this->page->delete($id);
						$this->get('cache')->delete('pages');
					}
					message('Pages deleted !', 'success');
					break;
				default:
					break;
			}
		}

		// get data
		$cache = $this->get('cache');
		if( ! $cache->contains('pages')) {
			$cache->save('pages', $this->page->all());
		}
		$pages = $cache->fetch('pages');

		// prepare asset
		$this->get('asset')
		->css('@bootstrap_datatables_css')
		->js('@datatables_js')
		->js('@bootstrap_datatables_js')
		->js('@jquery_check_all')	
		->js($this->publicPath('js/admin-index.js'));

		$toolbar = array(
			'new-post' => array(
				'type' => 'a.success',
				'href' => admin_url('pages/create'),
				'label' => 'New Page',
				'faClass' => 'fa-plus'
			),
			'trash' => array(
				'type' => 'submit',
				'label' => 'Trash',
				'name'=> 'action',
				'value' => 'trash',
				'faClass' => 'fa-trash-o'
			),
		);
		$filters = [[
			'' => '- Status -',
			0 => 'Unpublished',
			1 => 'Published',
			2 => 'Trashed',
		]];

		$editUrl = admin_url('pages/edit');
		$header = [
			[
				'field' => 'title',
				'label' => 'Title',
				'width' => '80%',
				'format' => function($value, $item) use ($editUrl) {return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; }
			],
			[
				'field' => 'created_at',
				'label' => 'Date',
				'width' => '20%'
			]
		];

		return $this->layoutList('pages', __('Pages'), null, null, $toolbar, $header, $pages, $filters);
	}

	public function create()
	{
		$this->auth->restrict('page.add');		
		if ($postData = $this->get('input')->post()) {			
			try {
				$this->validate('page', $postData);

				$data = $this->createInsertData($postData);
				$id = $this->page->insert($data);

				$this->get('cache')->delete('pages');

				message('Post succesfully saved.', 'success');

			} catch (\Exception $e) {
				message($e->getMessage(), 'error');
			}
		}

		//set default
		set(array(
			'id' => null,
			'title' => null,
			'slug' => null,
			'content' => null,
			'layout' => null,
			'layoutOptions' => $this->getLayoutOptions(),
			'status' => 1,
			)
		);
		
		$this->get('asset')
			->css($this->publicPath('css/editor.css'))
			->js($this->publicPath('js/editor.js'));

		$toolbar = array('save-draft' => array(
				'type' => 'submit.success',
				'label' => 'Save',
				'name'=> 'action',
				'value' => 'save',
				'faClass' => 'fa-check'
			),
			'cancel' => array(
				'type' => 'a.default',
				'href' => admin_url('pages'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
		);

		$inputView = $this->render('@pages/admin/edit-input', $this->getData());
		return $this->layoutForm(__('New Page'), null, null,  $toolbar, $inputView);
	}

	public function edit($id)
	{
		$this->auth->restrict('page.edit');
		
		if ($postData = $this->get('input')->post() ) {

			try {
				$this->validate('page', $postData);
				$data = $this->createUpdateData($postData);
				$this->page->update($data, $id);
				$this->get('cache')->delete('pages');
				message('Post succesfully updated.', 'success');

			} catch (\Exception $e) {
				message($e->getMessage(), 'error');
			}
		}

		$page = $this->page->getSingleBy('id', $id);

		set(array(
			'id' => $id,
			'title' => $page->title,
			'slug' => $page->slug,
			'content' => $page->content,
			'layout' => $page->layout,
			'layoutOptions' => $this->getLayoutOptions(),
			'status' => $page->status,
		));

		$this->get('asset')
		->css($this->publicPath('css/editor.css'))
		->js($this->publicPath('js/editor.js'));

		$toolbar = array(
			'update' => array(
				'type' => 'submit.success',
				'label' => 'Update',
				'name'=> 'action',
				'value' => 'update',
				'faClass' => 'fa-check'
			),
			'cancel' => array(
				'type' => 'a.default',
				'href' => admin_url('pages'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
		);
		$inputView = $this->render('@pages/admin/edit-input', $this->getData());

		return $this->layoutForm(__('Edit Page'), null, null,  $toolbar, $inputView);
	}

	/**
	 * get available layout from current themes
	 *
	 * @return array
	 */
	private function getLayoutOptions()
	{
		$layouts = $this->get('finder')->in($this->get('path.theme').'layout')->files();
		$options = array();
		foreach ($layouts as $layout) {
			$options[$layout->getFileName()] = $layout->getFileName();
		}

		return $options;
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
		$data['layout'] = $post['layout'];
		$data['status'] = $post['status'];
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
}