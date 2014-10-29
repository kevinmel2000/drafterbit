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
					return $value.'<br/>'.$item->email;
				}],
			[
				'field' => 'content',
				'label' => 'Comment',
				'width' => '50%',
				'format' => function($value, $item)
				{
					return $value;
				}],
			[
				'field' => 'status',
				'label' => 'Status',
				'width' => '15%',
				'format' => function($value, $item){
					switch ($value) {
						case 0:
							return 'Pending';
							break;
						case 1:
							return 'Approved';
							break;
						case 2:
							return 'Spam';
							break;
						default:
							break;
					}
				}
			],
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
}