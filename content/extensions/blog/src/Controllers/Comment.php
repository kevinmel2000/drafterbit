<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Comment extends BackendController {

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
				'field' => 'name',
				'label' => 'Author',
				'width' => '15%',
				'format' => function($value, $item){
					return "{$value} <div><a href=\"mailto:{$item->email}\">{$item->email}</a></div>";
				}],
			[
				'field' => 'content',
				'label' => 'Comment',
				'width' => '65%',
				'format' => function($value, $item)
				{
					$content = "$value";
					$content .= "<div class=\"comment-action\">";

					$display = $item->status == 1 ? 'inline' : 'none';
					$display2 = $item->status == 0 ? 'inline' : 'none';

					$content .=" <a data-status=\"0\" data-id=\"{$item->id}\" style=\"display:{$display}\" class=\"unapprove status\" href=\"#\">Pending</a>";
					$content .=" <a data-status=\"1\" data-id=\"{$item->id}\" style=\"display:{$display2}\" class=\"approve status\" href=\"#\">Approve</a>";
					$content .=" <a data-id=\"{$item->id}\" data-post-id=\"{$item->post_id}\" class=\"reply\" href=\"#\">Reply</a>";
					$content .=" <a data-status=\"2\" data-id=\"{$item->id}\" class=\"spam status\" href=\"#\">Spam</a>";
					$content .=" <a data-id=\"{$item->id}\" class=\"trash\" href=\"#\">Trash</a>";
					$content .="</div>";

					return $content;
				}],
			[
				'field' => 'post_id',
				'label' => 'In Respose to',
				'width' => '20%',
				'format' => function($value, $item){
					return '<a href="'.admin_url('blog/edit/'.$value).'">'.$item->title.'</a><br/>'.$item->created_at;
				}
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
		$data['created_at'] = \Carbon\Carbon::now();

		$id = $this->model('@blog\Comment')->insert($data);

		$referer = $this->get('input')->headers('referer');

		return redirect($referer.'#comment-'.$id);
	}

	public function status()
	{
		$id = $this->get('input')->post('id');
		$status = $this->get('input')->post('status');

		$this->model('Comment')->changeStatus($id, $status);
	}

	public function quickReply()
	{
		$data['post_id'] = $this->get('input')->post('postId');
		$data['content'] = $this->get('input')->post('comment');
		$data['parent_id'] = $this->get('input')->post('parentId');

		$session = $this->get('session');
		$data['user_id'] = $session->get('user.id');
		$data['name'] = $session->get('user.name');
		$data['email'] = $session->get('user.email');
		$data['created_at'] = \Carbon\Carbon::now();

		$id = $this->model('@blog\Comment')->insert($data);

		return $this->jsonResponse(['msg' => 'Comment saved', 'status' => 'success']);
	}

	public function quickTrash()
	{
		$id = $this->get('input')->post('id');
		$this->model('@blog\Comment')->trash($id);
		return $this->jsonResponse(['msg' => 'Comment moved to trash', 'status' => 'warning']);
	}
}