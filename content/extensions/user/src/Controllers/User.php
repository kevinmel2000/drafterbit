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
					return $value == 1 ? __('active') : __('blocked'); }],
			['field' => 'groups', 'label' => 'Group']
		);
	}


	public function create()
	{
		$this->model('@user\Auth')->restrict('user.add');
		$groups = $this->model('@user\UsersGroup')->all();
		$data['groupOptions'] =  $groups;
		$data['id'] = 'user-create';
		$data['title'] = __('Create New User');
		$data['action'] = admin_url('user/save');

		return $this->render('@user/admin/create', $data);
	}

	public function save()
	{
		try {

			$postData = $this->get('input')->post();

			$this->validate('user', $postData);

			if($this->model('@user\User')->getByEmail($postData['email'])) {
				throw new ValidationFailsException('That email was registered.');
			}
			
			$insertData = $this->createInsertData($postData);
			$id = $this->model('@user\User')->insert($insertData);

			//insert group
			$this->insertGroups( $postData['groups'], $id );

			if( isset($postData['send-password'])) {
				$this->sendPassword( $postData['email'], $postData['mail-message'], $postData['password']);
			}

			$data['message'] = 'User saved !';
			$data['status'] = 'success';

		} catch ( ValidationFailsException $e) {
			$data['message'] = $e->getMessage();
			$data['status'] = 'error';
		
		} catch ( \Swift_SwiftException $e) {

			$data['message'] = "User saved, but email was not sent due to error: {$e->getMessage()}";
			$data['status'] = 'warning';
		}

		return new JsonResponse($data);
	}

	public function edit($id = null)
	{
		$this->model('@user\Auth')->restrict('user.edit');

		$groups = $this->model('@user\UsersGroup')->all();

		$postData = $this->get('input')->post();

		if ($postData) {
			try {
				$this->validate('user', $postData);
				
				$data = $this->createUpdateData($postData);
				$this->model('@user\User')->update($data, array('id' => $id));

				//insert group
				$this->insertGroups( $postData['groups'], $id );

				message('User Updated !','success');

			} catch ( ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			}
		}

		$user = $this->model('@user\User')->getSingleBy('id', $id);
		$user->groupIds = $this->model('@user\User')->getGroupIds($user->id);
		
		set([
			'groupOptions' => $groups,
			'realName' => $user->real_name,
			'email' => $user->email,
			'website' => $user->website,
			'bio' => $user->bio,
			'groupIds' => $user->groupIds,
			'active' => $user->status,
			'userId' => $user->id,
			'id' => 'pages-edit',
			'title' => __('Edit Pages')
		]);
	
		
		return $this->render('@user/admin/edit', $this->getData());
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
		$data['status'] = isset($post['active']) ? $post['status'] : 1;
		$data['website'] = isset($post['website']) ? $post['website'] : null;
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

	protected function sendPassword($email, $messageBody, $password)
	{
		if( is_null($messageBody) or trim($messageBody) == '') {
			$messageBody = sprintf("this is your password: %s", $password);
		} else {
			$messageBody = sprintf($messageBody, $password);
		}

		$fromEmail = $this->get('config')->get('mail.from');

		$message = $this->get('mail')
			->setSubject('Registrar Confirmation')
			->setFrom($fromEmail)
			->setTo($email)
			->setBody($messageBody);
	
		return $this->get('mailer')->send($message);
	}
}