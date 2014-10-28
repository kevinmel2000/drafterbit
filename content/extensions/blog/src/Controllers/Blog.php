<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;
use Carbon\Carbon;

class Blog extends BackendController {

	public function index($status = 'published')
	{
		// authorize restriction
		$this->model('@user\Auth')->restrict('blog.view');

		// handle request
		if($post = $this->get('input')->post()) {

			if(isset($post['post']) and count($post['post']) > 0) {
				$this->_handleIndexRequest($post['post'], $post['action']);
			} else {
				message('Please make selection first', 'error');
			}
		}

		$posts = $this->model('@blog\Post')->all(['status' => $status]);

		set('status', $status);
		set('title', __('Blog'));
		set('id', 'posts');
		set('blogTable', $this->datatables('posts', $this->_table(), $posts));

		return $this->render('@blog/admin/index', $this->getData());
	}

	private function _handleIndexRequest($postIds, $action)
	{
		switch($action) {
				case "delete":

					// $ids = '';
					 foreach ($postIds as $id) {
					// 	$ids .= ", $id";
					 	$this->model('@blog\Post')->delete($id);
					 }
					// $ids = ltrim($ids, ',');

					// $userId = $this->get('session')->get('uid');
					// $userName = $this->get('session')->get('user.name');
					// $userUrl = base_url("user/edit/$userId");
					// log_db("Delete Post", "<a href='{$userUrl}'>{$userName}</a>","with id(s) : {$ids}");
					$this->refreshCache();
					message('posts deleted !', 'success');
					break;
				case 'trash':
					$data['status'] = 'trashed';
					foreach ($postIds as $id) {
						$this->model('@blog\Post')->update($data, $id);
					}

					$this->refreshCache();
					message('posts trashed !', 'success');
					break;
				case 'restore':
					$data['status'] = 'published';
					foreach ($postIds as $id) {
						$this->model('@blog\Post')->update($data, $id);
					}

					$this->refreshCache();
					message('posts restored !', 'success');
					break;
				default:
					break;
			}
	}

	private function refreshCache()
	{
		$this->get('cache')->delete('posts.published');
		$this->get('cache')->delete('posts.trashed');
	}

	private function _table()
	{
		$editUrl = admin_url('blog/edit');
		$userUrl = admin_url('user/edit');

		return array(
			[
				'field' => 'title',
				'label' => 'Title',
				'width' => '40%',
				'format' => function($value, $item) use ($editUrl) {
					return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; }],
			[
				'field' => 'authorName',
				'label' => 'Author',
				'width' => '20%',
				'format' => function($value, $item) use ($userUrl) {
					return "<a href='$userUrl/{$item->user_id}'>$value</a>"; }],
			[
				'field' => 'status',
				'label' => 'Status',
				'width' => '20%',
				'format' => function($value, $item) use ($userUrl) {
					return ucfirst($value); }],
			[
				'field' => 'created_at',
				'label' => 'Created',
				'width' => '20%',
				'format' => function($value, $item){ return $value; }],
		);
	}

	public function create()
	{
		// restrict
		$this->model('@user\Auth')->restrict('blog.add');

		// handle request
		if ($post = $this->get('input')->post()) {
			$this->_handleCreateRequest($post);
		}

		//get data
		$tagOptionsArray = $this->model('@blog\Tag')->all();
		$tagOptions = '[';
		foreach ($tagOptionsArray as $tO) {
			$tagOptions .= "'{$tO->label}',";
		}
		$tagOptions = rtrim($tagOptions, ',').']';

		set(array(
			'postId' => null,
			'postTitle' => null,
			'slug' => null,
			'content' => null,
			'tagOptions' => $tagOptions,
			'tags' => array(),
			'title' => __('New Post'),
			'id' => 'post-create'
		));

		return $this->render('@blog/admin/edit', $this->getData());
	}

	private function _handleCreateRequest($postData)
	{
		try {
				$this->validate('blog', $postData);
				
				$data = $this->createInsertData($postData);
				$id = $this->model('@blog\Post')->insert($data);

				if(isset($postData['tags']))
				$this->insertTags( $postData['tags'], $id);

				//clear cache
				$this->get('cache')->delete('posts');

				$postUrl = base_url("admin/blog/edit/$id");
				$title = $postData['title'];
				$userUrl = base_url("admin/user/edit/{$data['user_id']}");
				$userName = $this->get('session')->get('user.name');
				//log_db("Create Post", "<a href='{$userUrl}'>{$userName}</a>","<a href='$postUrl'>{$title}</a>");
				
				$msg['text'] = 'Post successfully saved';
				$msg['type'] = 'success';
				$this->get('session')
					->getFlashBag()->set('message', $msg);
				return redirect(base_url("admin/blog/edit/$id"));

			} catch (ValidationFailsException $e) {

				message($e->getMessage(), 'error');
			}
	}

	public function edit($id)
	{
		$this->model('@user\Auth')->restrict('blog.edit');
		
		if ($postData = $this->get('input')->post()) {

			try {
				$this->validate('blog', $postData);
				$data = $this->createUpdateData($postData);
				$this->model('@blog\Post')->update($data, $id);

				$this->insertTags( $postData['tags'], $id);
				message('Post succesfully updated.', 'success');

			} catch (\Exception $e) {
				message($e->getMessage(), 'error');
			}
		}

		//options
		$tagOptionsArray = $this->model('@blog\Tag')->all();
		$tagOptions = '[';
		foreach ($tagOptionsArray as $tO) {
			$tagOptions .= "'{$tO->label}',";
		}
		$tagOptions = rtrim($tagOptions, ',').']';

		$post = $this->model('@blog\Post')->getBy('id', $id);

		//used
		$post->tags = $this->model('@blog\Post')->getTags($id);
		
		$tags = array();
		foreach ($post->tags as $tag) {
			$tags [] = $tag->label;
		}

		set(array(
			'postId' => $id,
			'postTitle' => $post->title,
			'slug' => $post->slug,
			'content' => $post->content,
			'tags' => $tags,
			'tagOptions' => $tagOptions,
			'title' => __('Edit Post'),
			'id' => 'post-create'
		));
		
		return $this->render('@blog/admin/edit', $this->getData());
	}

	public function delete(){
		$this->model('@user\Auth')->restrict('blog.delete');
		//..
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
		$data['user_id'] = $this->get('session')->get('user.id');
		$data['updated_at'] = Carbon::now();
		$data['status'] = ($post['action'] == 'publish') ? 'published' : 'unpublished';
		
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

	protected function insertTags($tags, $postId)
	{
		//delete all related tag first
		$this->model('@blog\Post')->clearTag($postId);

		foreach ($tags as $tag) {
			if( ! $tagId = $this->model('@blog\Tag')->getIdBy('label', $tag)) {
				$tagId = $this->model('@blog\Tag')->save($tag);
			}

			$this->model('@blog\Post')->addTag($tagId, $postId);
		}
	}
}