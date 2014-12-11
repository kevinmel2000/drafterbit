<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;
use Carbon\Carbon;

class Blog extends BackendController {

	public function index()
	{		
		$status = 'untrashed';

		$posts = $this->model('@blog\Post')->all(['status' => $status]);

		$data['status'] = $status;
		$data['title'] = __('Blog');
		$data['id'] = 'posts';
		$data['action'] = admin_url('blog/trash');
		$data['blogTable'] = $this->datatables('posts', $this->_table(), $posts);

		return $this->render('@blog/admin/index', $data);
	}

	public function trash()
	{
		$post = $this->get('input')->post();

		$postIds = $post['posts'];

		switch($post['action']) {
			case "trash":
				$this->model('@blog\Post')->trash($postIds);
				break;
			case 'delete':
				$this->model('@blog\Post')->delete($postIds);
			case 'restore':
				$this->model('@blog\Post')->restore($postIds);
			break;
			default:
				break;
		}
	}

	public function filter($status)
	{
		$posts = $this->model('@blog\Post')->all(['status' => $status]);
		
		$editUrl = admin_url('blog/edit');

		$pagesArr  = array();

		foreach ($posts as $post) {
			$data = array();
			$data[] = '<input type="checkbox" name="posts[]" value="'.$post->id.'">';
			$data[] = $status !== 'trashed' ? "<a class='post-edit-link' href='$editUrl/{$post->id}'>{$post->title}</a>" : $post->title;
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
					return "<a href='$editUrl/{$item->id}'>$value</a>"; }],
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
			return $this->jsonResponse(['message' => __('Post succesfully saved'), 'status' => 'success', 'id' => $id]);

		} catch (ValidationFailsException $e) {

			return $this->jsonResponse(['error' => [
					'type' => 'validation',
					'message' => $e->getMessage(),
					'messages' => $e->getMessages()
				]
			]);
		}
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