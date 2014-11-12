<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;

class User extends BackendController {

	public function index()
	{
		$this->model('@user\Auth')->restrict('user.view');
		$userIds = $this->get('input')->post('users');

		if($userIds) {
			$action = $this->get('input')->post('action');

			switch($action) {
				case "Delete":
					foreach ($userIds as $id) {
						$this->model('@user\User')->delete($id);
					}
					message('Users deleted !', 'success');
					break;
				default:
					break;
			}
		}

		$users = $this->model('@user\User')->all();

		foreach ($users as $user) {
			$user->groups = $this->model('@user\UsersGroup')->getByUser($user->id);
		}

		set('users', $users);
		set('id', 'users');
		set('title', __('Users'));
		set('usersTable', $this->datatables('users', $this->_table(), $users));

		return $this->render('@user/admin/index', $this->getData());
	}

	private function _table()
	{
		$editUrl = admin_url('user/edit');

		return array(
			['field' => 'real_name', 'label' => 'Name', 'format' => function($value, $item) use ($editUrl) {
					return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; }],
			['field' => 'email', 'label' => 'Email'],
			['field' => 'status', 'label' => 'Status', 'format' => function($value, $item) {
					return $value == 1 ? __('active') : __('banned'); }],
			//['field' => 'groups', 'label' => 'Group']
		);
	}

	public function save()
	{
		try {

			$postData = $this->get('input')->post();

			$this->validate('user', $postData);
			
			$id = $postData['id'];

			if($id) {
				$updateData = $this->createUpdateData($postData);
				$this->model('@user\User')->update($updateData, ['id' => $id]);
			
			} else {

				$insertData = $this->createInsertData($postData);
				$id = $this->model('@user\User')->insert($insertData);
			}

			//insert group
			$this->insertGroups( $postData['groups'], $id );

			$data['id'] = $id;
			$data['message'] = 'User saved !';
			$data['status'] = 'success';

		} catch ( ValidationFailsException $e) {
			$data['message'] = $e->getMessage();
			$data['status'] = 'error';
		
		}

		return new JsonResponse($data);
	}

	public function edit($id = null)
	{
		$this->model('@user\Auth')->restrict('user.edit');

		if($id == 'new') {

			$data = array(
				'realName' => null,
				'email' => null,
				'url' => null,
				'bio' => null,
				'groupIds' => array(),
				'status' => null,
				'userId' => null,
				'title' => __('New User')
			);
		} else {

			$user = $this->model('@user\User')->getSingleBy('id', $id);
			$user->groupIds = $this->model('@user\User')->getGroupIds($user->id);

			$data = array(
				'realName' => $user->real_name,
				'email' => $user->email,
				'url' => $user->url,
				'bio' => $user->bio,
				'groupIds' => $user->groupIds,
				'status' => $user->status,
				'userId' => $user->id,
				'title' => __('New User')
			);
		}

		$groups = $this->model('@user\UsersGroup')->all();

		$data['groupOptions'] = $groups;
		$data['id'] = 'user-edit';
		$data['action'] = admin_url('user/save');
		
		return $this->render('@user/admin/edit', $data);
	}

	protected function insertGroups($groups, $id)
	{
		$this->model('@user\User')->clearGroups($id);

		foreach($groups as $group) {
			$this->model('@user\User')->insertGroup($group, $id);
		}
	}

	protected function createInsertData($post, $update = false)
	{
		$data = array();
		$data['email'] = $post['email'];
		
		if(isset($post['password']) and trim($post['password']) !== '') {
			$data['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
		}
		
		$data['bio'] = isset($post['bio']) ? $post['bio'] : null;
		$data['status'] = $post['status'];
		$data['url'] = isset($post['url']) ? $post['url'] : null;
		$data['real_name'] = isset($post['real-name']) ? $post['real-name'] : null;
		$data['updated_at'] = Carbon::Now();

		if( ! $update) {
			$data['created_at'] = Carbon::Now();
		}

		return $data;
	}

	protected function createUpdateData($post)
	{
		return $this->createInsertData($post, true);
	}
}