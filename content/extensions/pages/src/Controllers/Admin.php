<?php namespace Drafterbit\Extensions\Pages\Controllers;

use Drafterbit\Extensions\System\BaseController;
use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\Pages\Models\Pages as PageModel;
use Models\Post;
use Carbon\Carbon;

class Admin extends BaseController {
	
	protected $pages;

	public function __construct(Auth $auth, PageModel $pages)
	{
		parent::__construct($auth);
		$this->pages = $pages;
	}

	public function index($status = 'untrashed')
	{
		$this->auth->restrict('page.view');

		if( $post = $this->get('input')->post()) {

			$pageIds = $post['pages'];

			switch($post['action']) {
				case "trash":
					$this->pages->trash($pageIds);
					message('Pages moved to trash !', 'success');
					break;
				case 'delete':
					$this->pages->delete($pageIds);
					message('Pages Deleted !', 'success');
				case 'restore':
					$this->pages->restore($pageIds);
					message('Pages Restored !', 'success');
				break;
				default:
					break;
			}
		}
		
		$pages = $this->pages->all($status);

		// prepare asset
		$this->get('asset')
		->css('@bootstrap_datatables_css')
		->css($this->publicPath('css/index.css'))
		->js('@datatables_js')
		->js('@bootstrap_datatables_js')
		->js('@jquery_check_all')	
		->js('@jquery_form')
		->js($this->publicPath('js/admin-index.js'));

		set('status', $status);
		set('table', $this->tableHeader('pages', $pages, $this->_tableHeader()));
		set('header', $this->header( __('Pages')));

		return view();
	}

	public function filter($status)
	{
		$search = $this->get('input')->get('search');

		$pages = $this->pages->all($status);
		
		$editUrl = admin_url('pages/edit');

		$pagesArr  = array();

		foreach ($pages as $page) {
			$data = array();
			$data[] = '<input type="checkbox" name="pages[]" value="'.$page->id.'">';
			$data[] = $status !== 'trashed' ? "<a class='page-edit-link' href='$editUrl/{$page->id}'> {$page->title} <i class='fa fa-edit edit-icon'></i></a>" : $page->title;
			$data[] = $page->created_at;

			if($status == 'trashed') {
				$s = ucfirst($status);
			} else {
				$s = $page->status == 1 ? 'Published' : 'Unpublished';
			}

			$data[] = $s;

			$pagesArr[] = $data;
		}

		$ob = new \StdClass;
		$ob->data = $pagesArr;
  		$ob->recordsTotal= count($pagesArr);
		$ob->recordsFiltered = count($pagesArr);

		return json_encode($ob);
	}

	/**
	 * Save submitted post data
	 */
	public function save()
	{
		var_dump($this->get('input')->post());
	}

	private function _tableHeader()
	{
		$editUrl = admin_url('pages/edit');
		$formatTitle = function($value, $item) use ($editUrl) {return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; };
		$formatStatus = function($value, $item) {return $value == 1 ? 'Published' : 'Unpublished'; };

		return [
			['field' => 'title', 'label' => 'Title', 'width' => '70%', 'format' => $formatTitle ],
			['field' => 'created_at', 'label' => 'Created', 'width' => '20%'],
			['field' => 'status', 'label' => 'Status', 'width' => '10%', 'format' => $formatStatus ]
		];
	}

	public function create()
	{
		$this->auth->restrict('page.add');		
		if ($postData = $this->get('input')->post()) {			
			try {
				$this->validate('page', $postData);

				$data = $this->createInsertData($postData);
				$id = $this->pages->insert($data);

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
			->js('@jquery_form')
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
				'href' => admin_url('pages/index'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
		);

		$inputView = $this->render('@pages/admin/editor', $this->getData());
		return $this->layoutForm('page-create', __('New Page'), null, admin_url('pages/save'),  $toolbar, $inputView);
	}

	public function edit($id)
	{
		$this->auth->restrict('page.edit');
		
		if ($postData = $this->get('input')->post() ) {

			try {
				$this->validate('page', $postData);
				$data = $this->createUpdateData($postData);
				$this->pages->update($data, $id);
				$this->get('cache')->delete('pages');
				message('Post succesfully updated.', 'success');

			} catch (\Exception $e) {
				message($e->getMessage(), 'error');
			}
		}

		$page = $this->pages->getSingleBy('id', $id);

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
				'href' => admin_url('pages/index'),
				'label' => 'Cancel',
				'faClass' => 'fa-times',
				'faStyle' => 'color: #A94442;',
			),
		);
		$inputView = $this->render('@pages/admin/editor', $this->getData());

		return $this->layoutForm('pages-edit', __('Edit Page'), null, null,  $toolbar, $inputView);
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