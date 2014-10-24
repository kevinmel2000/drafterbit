<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\BaseController as BaseController;

class Comment extends BaseController {

	public function index()
	{
		$model = $this->model('Comment');

		$comments = $model->all();

		set('id', 'comments');
		set('title', __('Comments'));
		set('status', 1);
		set('commentsTable', $this->datatables('comments', $this->_table(), $comments));

		return $this->render('@blog/admin/comments/index', $this->getData());
	}

	private function _table()
	{
		return array(
			[
				'field' => 'user_id',
				'label' => 'Author',
				'width' => '40%'],
			[
				'field' => 'content',
				'label' => 'Content',
				'width' => '20%'],
			[
				'field' => 'status',
				'label' => 'Status',
				'width' => '20%',
			],
			[
				'field' => 'post_id',
				'label' => 'In Respose to',
				'width' => '20%'
			]
		);
	}

	public function submit()
	{
		$comments = $this->get('input')->post('comment');

		$data['name'] = $comments['name'];
		$data['email'] = $comments['email'];
		$data['url'] = $comments['url'];
		$data['content'] = $comments['content'];
		$data['parent_id'] = $comments['parent_id'];
		$data['post_id'] = $comments['post_id'];

		$id = $this->model('@blog\Comment')->insert($data);

		$referer = $this->get('input')->headers('referer');

		return redirect($referer.'#comment-'.$id);
	}
}