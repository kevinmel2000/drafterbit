<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;
use Carbon\Carbon;

class Blog extends BackendController {

	public function index()
	{
		$this->model('@user\Auth')->restrict('blog.view');
		
		$status = 'untrashed';

		/*if($post = $this->get('input')->post()) {

			if(isset($post['post']) and count($post['post']) > 0) {
				$this->_handleIndexRequest($post['post'], $post['action']);
			} else {
				message('Please make selection first', 'error');
			}
		}*/

		$posts = $this->model('@blog\Post')->all(['status' => $status]);

		$data['status'] = $status;
		$data['title'] = __('Blog');
		$data['id'] = 'posts';
		$data['blogTable'] = $this->datatables('posts', $this->_table(), $posts);

		return $this->render('@blog/admin/index', $data);
	}

	public function filter($status)
	{
		$posts = $this->model('@blog\Post')->all(['status' => $status]);
		
		$editUrl = admin_url('blog/edit');

		$pagesArr  = array();

		foreach ($posts as $post) {
			$data = array();
			$data[] = '<input type="checkbox" name="posts[]" value="'.$post->id.'">';
			$data[] = $status !== 'trashed' ? "<a class='post-edit-link' href='$editUrl/{$post->id}'> {$post->title} <i class='fa fa-edit edit-icon'></i></a>" : $post->title;
			$data[] ='<a href="'.admin_url('blog/edit/'.$post->id).'">'.$post->authorName.'</a>';

			if($status == 'trashed') {
				$s = ucfirst($status);
			} else {
				$s = $post->status == 1 ? 'Published' : 'Unpublished';
			}

			$data[] = $s;
			$data[] = $post->created_at;

			$pagesArr[] = $data;
		}

		$ob = new \StdClass;
		$ob->data = $pagesArr;
		$ob->recordsTotal= count($pagesArr);
		$ob->recordsFiltered = count($pagesArr);

		return $this->jsonResponse($ob);
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
					return $value == 1 ? 'Published' : 'Unpublished'; }],
			[
				'field' => 'created_at',
				'label' => 'Created',
				'width' => '20%',
				'format' => function($value, $item){ return $value; }],
		);
	}

	public function edit($id)
	{
		$this->model('@user\Auth')->restrict('blog.edit');

		$tagOptionsArray = $this->model('@blog\Tag')->all();
		$tagOptions = '[';
		foreach ($tagOptionsArray as $tO) {
			$tagOptions .= "'{$tO->label}',";
		}
		$tagOptions = rtrim($tagOptions, ',').']';

		if('new' == $id) {

			$data = array(
				'postId' => null,
				'postTitle' => null,
				'slug' => null,
				'content' => null,
				'tagOptions' => $tagOptions,
				'tags' => array(),
				'status' => 1,
				'title' => __('New Post'),
			);
		} else {

			$post = $this->model('@blog\Post')->getBy('id', $id);
			$post->tags = $this->model('@blog\Post')->getTags($id);
			
			$tags = array();
			foreach ($post->tags as $tag) {
				$tags [] = $tag->label;
			}

			$data = array(
				'postId' => $id,
				'postTitle' => $post->title,
				'slug' => $post->slug,
				'content' => $post->content,
				'tags' => $tags,
				'tagOptions' => $tagOptions,
				'status' => $post->status,
				'title' => __('Edit Post'),
			);
		}

		$data['id'] = 'post-edit';
		$data['action'] = admin_url('blog/save');
		
		return $this->render('@blog/admin/edit', $data);
	}

	public function save()
	{
		try {
				$postData = $this->get('input')->post();

				$this->validate('blog', $postData);

				$id = $postData['id'];

				if($id) {
					$data = $this->createUpdateData($postData);
					$this->model('@blog\Post')->update($data, $id);
				
				} else {
					$data = $this->createInsertData($postData);				
					$id = $this->model('@blog\Post')->insert($data);
				}

				if(isset($postData['tags'])) {
					$this->insertTags( $postData['tags'], $id);
				}

				// @todo log here
				return $this->jsonResponse(['msg' => __('Post succesfully saved'), 'status' => 'success', 'id' => $id]);

			} catch (ValidationFailsException $e) {
				return $this->jsonResponse(['msg' => $e->getMessage(), 'status' => 'error']);
			}	
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
		$data['status'] = $post['status'];
		
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