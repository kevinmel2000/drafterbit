<?php namespace Drafterbit\Extensions\User\Controllers\Admin;

use Drafterbit\Framework\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\Admin\BaseController;
use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\User\Models\UsersGroup;
use Drafterbit\Extensions\User\Models\User as UserModel;

class Group extends BaseController {

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
		$this->auth->restrict('usergroup.view');

		$groups = $this->get('input')->post('groups');

		if($groups) {
			$action = $this->get('input')->post('action');

			switch($action) {
				case "Delete":
					foreach ($groups as $group) {
						$this->group->delete($group);
					}
					message('Groups deleted !', 'success');
					break;
				default:
					break;
			}
		}

		if(! $this->get('cache')->contains('groups')) {
			$this->get('cache')->save('groups', $this->group->all());
		}
		$groups =$this->get('cache')->fetch('groups');
		
		set('groups', $groups);

		$this->get('asset')
		->css('@bootstrap_datatables_css')
		
		->js('@datatables_js')
		->js('@bootstrap_datatables_js')
		->js('@jquery_check_all')
		->js($this->publicPath('js/group/admin-index.js'));

		
		$editUrl = admin_url('user/group/edit');
		$tableConfig = array(
			['field' => 'label', 'label' => 'Group', 'format' => function($value, $item) use ($editUrl)  {return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>";}],
			['field' => 'description', 'label' => 'Description']
		);

		return $this->layoutList('groups', __('Group'),null, null, $this->_toolbarIndex(), $tableConfig, $groups);
	}

	function _toolbarIndex()
	{
		return array(
			'new' => array(
				'type' => 'a.success',
				'href' => admin_url('user/group/create'),
				'label' => 'New Group',
				'faClass' => 'fa-plus'
			),

			'delete' => array(
				'type' => 'submit',
				'label' => 'Delete',
				'name'=> 'action',
				'value' => 'delete',
				'faClass' => 'fa-trash-o'
			),


		);
	}

	function _toolbarEdit()
	{
		return array(
			'cancel' => array(
				'type' => 'a',
				'href' => admin_url('user/group'),
				'label' => 'Cancel',
				'faClass' => 'fa-times'
			),

			'update' => array(
				'type' => 'submit.success',
				'label' => 'Update',
				'name'=> 'action',
				'value' => 'update',
				'faClass' => 'fa-check'
			),


		);
	}

	function _toolbarCreate()
	{
		return array(
			'cancel' => array(
				'type' => 'a',
				'href' => admin_url('user/group'),
				'label' => 'Cancel',
				'faClass' => 'fa-times'
			),

			'update' => array(
				'type' => 'submit.success',
				'label' => 'Save',
				'name'=> 'action',
				'value' => 'save',
				'faClass' => 'fa-check'
			),


		);
	}

	public function delete($id)
	{
		$this->auth->restrict('usergroup.delete');
		$this->group->delete($id);
	}

	public function edit($id = null)
	{
		$this->auth->restrict('usergroup.edit');

		$posts = $this->get('input')->post();

		if($posts) {
			
			try {
				$this->validate('group', $posts);

				//insert froup
				$data = $this->createGroupsInsertData($posts);
				
				$this->group->update($data, array('id' => $id));

				//insert permission
				if(isset($posts['permissions'])) {

					$this->insertPermissions($posts['permissions'], $id);
				}

				message('Group updated !', 'success');

			} catch (ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			}
		}

		$group = $this->group->getsingleBy('id', $id);
		$group->permissionIds = $this->group->getPermissionIds($id);

		$permissions = $this->group->getPermission()->all();
		set([
			'groupName' => $group->label,
			'description' => $group->description,
			'permissions' => $permissions,
			'permissionIds' => $group->permissionIds
		]);

		$ui = $this->model('UI@admin');
		$header =  $ui->header('Edit Group', 'Edit Group');
		$toolbar = $ui->toolbar($this->_toolbarEdit());
		$view = $this->render('admin/group/edit', $this->getData());
		$form = $ui->form(null, $toolbar, $view);
		$content = $header.$form;

		return $this->wrap($content);
	}

	public function create()
	{
		$this->auth->restrict('usergroup.add');

		$posts = $this->get('input')->post();

		if ($posts) {
			try {
				$this->validate('group', $posts);

				//insert group
				$data = $this->createGroupsInsertData($posts);
				$id = $this->group->insert($data);

				//insert permission
				if(isset($posts['permissions'])) {
					$this->insertPermissions($posts['permissions'], $id);
				}

				$msg['text'] = 'Group saved successfully';
				$msg['type'] = 'success';
				$this->get('session')
					->getFlashBag()->set('message', $msg);
				return redirect(admin_url("user/group/edit/$id"));

			} catch (ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			}
		}

		set([
			'groupName' => null,
			'description' => null,
			'permissionIds' => array()
		]);

		$permissions = $this->group->getPermission()->all();
		set(['permissions' => $permissions ]);

		$ui = $this->model('UI@admin');
		$header =  $ui->header('New Group', 'Create new group');
		$toolbar = $ui->toolbar($this->_toolbarCreate());
		$view = $this->render('admin/group/edit', $this->getData());
		$form = $ui->form(null, $toolbar, $view);
		$content = $header.$form;

		return $this->wrap($content);
	}

	protected function createGroupsInsertData($post)
	{
		$data = array();
		$data['label'] = $post['name'];
		$data['description'] = $post['description'];
		return $data;
	}

	protected function insertPermissions($permissions, $id)
	{
		$this->group->clearPermissions($id);

		foreach ($permissions as $permission) {
			$this->group->insertPermission($permission, $id);
		}
	}
}