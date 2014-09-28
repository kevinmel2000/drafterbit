<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Framework\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\Admin\BaseController;
use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\User\Models\User as UserModel;
use Drafterbit\Extensions\User\Models\UsersGroup;
use Carbon\Carbon;

class Admin extends BaseController {

	protected $user;
	protected $group;

	public function __construct( Auth $auth, UserModel $user, UsersGroup $group)
	{
		parent::__construct($auth);
		$this->user = $user;
		$this->group = $group;
	}

	public function index()
	{
		$this->auth->restrict('user.view');
		$userIds = $this->get('input')->post('users');

		if($userIds) {
			$action = $this->get('input')->post('action');

			switch($action) {
				case "Delete":
					foreach ($userIds as $id) {
						$this->user->delete($id);
					}
					message('Users deleted !', 'success');
					break;
				default:
					break;
			}
		}

		//$users = $this->user->all();

		// get data
		$cache = $this->get('cache');
		if( ! $cache->contains('users')) {
			$cache->save('users', $this->user->all());
		}
		$users = $cache->fetch('users');

		foreach ($users as $user) {
			$user->groups = $this->group->getByUser($user->id);
		}

		set('users', $users);

		$this->get('asset')->css('@bootstrap_datatables_css')
		
		->js('@datatables_js')
		->js('@bootstrap_datatables_js')
		->js('@jquery_check_all')
		->js($this->publicPath('js/index.js'));
		
		$ui = $this->model('UI@admin');
		$header 	=  $ui->header('User', 'User management');
		$table 		= $ui->datatables('user', $users, $this->_table());
		$toolbar 	= $ui->toolbar($this->_toolbarIndex());
		$listFormed = $ui->listFormed(null, $toolbar, $table);
		$content 	= $header.$listFormed;

		return $this->wrap($content);
	}

	private function _table()
	{
		$editUrl = admin_url('user/edit');

		return array(
			['field' => 'real_name', 'label' => 'Name', 'format' => function($value, $item) use ($editUrl) {
					return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>"; }],
			['field' => 'email', 'label' => 'Email']
		);
	}

	private function _toolbarIndex()
	{
		return array(
			'trash' => array(
				'type' => 'submit',
				'label' => 'Delete',
				'name'=> 'action',
				'value' => 'delete',
				'faClass' => 'fa-trash-o'
			),
			'new-post' => array(
				'type' => 'a.success',
				'href' => admin_url('user/create'),
				'label' => 'New User',
				'faClass' => 'fa-plus'
			),

		);
	}

	private function _toolbarEdit()
	{
		return array(
			'trash' => array(
				'type' => 'a',
				'href' => admin_url('user/create'),
				'label' => 'Delete',
				'faClass' => 'fa-trash-o'
			),
			'new-post' => array(
				'type' => 'submit.success',
				'label' => 'Update',
				'name'=> 'action',
				'value' => 'update',
				'faClass' => 'fa-check'
			),

		);
	}

	public function create()
	{
		$this->auth->restrict('user.create');
		$postData = $this->get('input')->post();

		if ($postData) {
			try {
				$this->validate('user', $postData);

				if($this->user->getByEmail($postData['email'])) {
					throw new ValidationFailsException('That email was registered.');
				}
				
				$data = $this->createInsertData($postData);
				$id = $this->user->insert($data);
				set('justSaved', true);

				//insert group
				$this->insertGroups( $postData['groups'], $id );

				if( isset($postData['send-password'])) {
					$this->sendPassword( $postData['email'], $postData['mail-message'], $postData['password']);
				}

				message('User saved !','success');

			} catch ( ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			
			} catch ( \Swift_SwiftException $e) {

				$message = "User saved, but email was not sent due to error: {$e->getMessage()}. You probably can send it manually.";
				message($message, 'warning');
			}
		}

		$groups = $this->group->all();
		set('groupOptions', $groups);

		$this->get('asset')
		->css('@chosen_bootstrap_css')
		->css('@chosen_css', '@chosen_css')
		->js('@chosen_js')
		->js($this->publicPath('js/create.js'));

		$ui = $this->model('UI@admin');
		$header 	=  $ui->header('User', 'User management');
		//$toolbar 	= $ui->toolbar($this->_toolbarCreate());
		$view 		= $this->render('admin/create', $this->getData());
		$form 		= $ui->form(null, null,$view);
		$content 	= $header.$form;

		return $this->wrap($content);
	}

	public function edit($id = null)
	{
		$this->auth->restrict('user.edit');
		$groups = $this->group->all();

		$postData = $this->get('input')->post();

		if ($postData) {
			try {
				$this->validate('user', $postData);
				
				$data = $this->createUpdateData($postData);
				$this->user->update($data, array('id' => $id));

				//insert group
				$this->insertGroups( $postData['groups'], $id );

				message('User Updated !','success');

			} catch ( ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			}
		}

		$user = $this->user->getSingleBy('id', $id);
		$user->groupIds = $this->user->getGroupIds($user->id);
		
		set([
			'groupOptions' => $groups,
			'realName' => $user->real_name,
			'email' => $user->email,
			'website' => $user->website,
			'bio' => $user->bio,
			'groupIds' => $user->groupIds,
			'active' => $user->active
		]);
		
		$this->get('asset')
		->css('@chosen_bootstrap_css')
		->css('@chosen_css', '@chosen_css')
		->js('@chosen_js')
		->js($this->publicPath('js/create.js'));

		$ui = $this->model('UI@admin');
		$header 	=  $ui->header('User', 'User management');
		$toolbar 	= $ui->toolbar($this->_toolbarEdit());
		$view 		= $this->render('admin/edit', $this->getData());
		$form 		= $ui->form(null, $toolbar,$view);
		$content 	= $header.$form;

		return $this->wrap($content);
	}

	protected function insertGroups($groups, $id)
	{
		$this->user->clearGroups($id);

		foreach($groups as $group) {
			$this->user->insertGroup($group, $id);
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
		$data['active'] = isset($post['active']) ? $post['active'] : 1;
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