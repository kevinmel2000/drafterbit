<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;

class Roles extends BackendController {

	public function index()
	{
		// /$this->model('@user\Auth')->restrict('roles.view');

		$roles = $this->model('@user\Role')->all();
		
		$data['roles'] = $roles;

		$editUrl = admin_url('user/roles/edit');
		$tableHead = array(
			['field' => 'label', 'label' => 'Role', 'format' => function($value, $item) use ($editUrl)  {return "<a href='$editUrl/{$item->id}'>$value</a>";}],
			['field' => 'description', 'label' => 'Description']
		);

		$data['id'] = 'roles';
		$data['title'] = __('Roles');
		$data['rolesTable'] = $this->datatables('roles', $tableHead, $roles);

		return $this->render('@user/admin/roles/index', $data);
	}

	public function indexAction()
	{

		$roles = $this->get('input')->post('roles');

		$action = $this->get('input')->post('action');

		switch($action) {
			case "Delete":
				foreach ($roles as $group) {
					$this->model('@user\Role')->delete($group);
				}
				message('Roles deleted !', 'success');
				break;
			default:
				break;
		}
	}

	public function delete($id)
	{
		$this->model('@user\Auth')->restrict('usergroup.delete');
		$this->model('@user\Role')->delete($id);
	}

	public function edit($id = null)
	{
		// @todo pending
		//$this->model('@user\Auth')->restrict('roless.edit');
	
		$data['id'] = 'roles-edit';
		$data['permissions'] = $this->model('@user\Role')->getPermission()->all();
		$data['action'] = admin_url('user/roles/save');;
		$data['roleId'] = $id;

		if($role = $this->model('@user\Role')->getsingleBy('id', $id)) {
			
			$role->permissionIds = $this->model('@user\Role')->getPermissionIds($id);
			
			$data['roleName'] = $role->label;
			$data['description'] = $role->description;
			$data['permissionIds'] = $role->permissionIds;
			$data['title'] = __('Edit Role');
		} else {
			$data['roleName'] = null;
			$data['description'] = null;
			$data['permissionIds'] = array();
			$data['title'] = __('New Role');
		}

		return $this->render('@user/admin/roles/edit', $data);
	}

	public function save()
	{
		$response = array();
			
		try {
			
			$posts = $this->get('input')->post();
			$this->validate('roles', $posts);

			//insert froup
			$data = $this->createRoleInsertData($posts);
			
			$id = $this->model('@user\Role')->save($posts['id'], $data);

			if(isset($posts['permissions'])) {
				$this->insertPermissions($posts['permissions'], $id);
			}

			$response = array(
				'message' => __('Role updated !'),
				'status' => 'success',
				'id' => $id
			);

		} catch (ValidationFailsException $e) {
			$response = array(
				'message' => $e->getMessage(),
				'status' => 'error'
			);
		}

		return $this->jsonResponse($response);
	}

	protected function createRoleInsertData($post)
	{
		$data = array();
		$data['label'] = $post['name'];
		$data['description'] = $post['description'];
		return $data;
	}

	protected function insertPermissions($permissions, $id)
	{
		$this->model('@user\Role')->clearPermissions($id);

		foreach ($permissions as $permission) {
			$this->model('@user\Role')->insertPermission($permission, $id);
		}
	}
}