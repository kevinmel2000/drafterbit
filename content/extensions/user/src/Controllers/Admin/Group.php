<?php namespace Drafterbit\Extensions\User\Controllers\Admin;

use Drafterbit\Framework\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;

class Group extends BackendController {

	public function index()
	{
		$this->model('@user\Auth')->restrict('usergroup.view');

		$groups = $this->get('input')->post('groups');

		if($groups) {
			$action = $this->get('input')->post('action');

			switch($action) {
				case "Delete":
					foreach ($groups as $group) {
						$this->model('@user\UsersGroup')->delete($group);
					}
					message('Groups deleted !', 'success');
					break;
				default:
					break;
			}
		}

		$groups = $this->model('@user\UsersGroup')->all();
		
		set('groups', $groups);

		$editUrl = admin_url('user/group/edit');
		$tableHead = array(
			['field' => 'label', 'label' => 'Group', 'format' => function($value, $item) use ($editUrl)  {return "<a href='$editUrl/{$item->id}'>$value <i class='fa fa-edit'></i></a>";}],
			['field' => 'description', 'label' => 'Description']
		);

		set('id', 'groups');
		set('title', __('Group'));
		set('groupTable', $this->datatables('groups', $tableHead, $groups));

		return $this->render('@user/admin/group/index', $this->getData());
	}

	public function delete($id)
	{
		$this->model('@user\Auth')->restrict('usergroup.delete');
		$this->model('@user\UsersGroup')->delete($id);
	}

	public function edit($id = null)
	{
		// @todo pending
		//$this->model('@user\Auth')->restrict('usergroup.edit');

		$posts = $this->get('input')->post();

		if($posts) {
			
			try {
				$this->validate('group', $posts);

				//insert froup
				$data = $this->createGroupsInsertData($posts);
				
				$this->model('@user\UsersGroup')->update($data, array('id' => $id));

				//insert permission
				if(isset($posts['permissions'])) {

					$this->insertPermissions($posts['permissions'], $id);
				}

				message('Group updated !', 'success');

			} catch (ValidationFailsException $e) {
				message($e->getMessage(), 'error');
			}
		}

		$group = $this->model('@user\UsersGroup')->getsingleBy('id', $id);
		$group->permissionIds = $this->model('@user\UsersGroup')->getPermissionIds($id);

		$permissions = $this->model('@user\UsersGroup')->getPermission()->all();
		set([
			'groupName' => $group->label,
			'description' => $group->description,
			'permissions' => $permissions,
			'permissionIds' => $group->permissionIds,
			'id' => 'group-edit',
			'title' => __('Edit Group'),
		]);

		return $this->render('@user/admin/group/edit', $this->getData());
	}

	public function create()
	{
		$this->model('@user\Auth')->restrict('usergroup.add');

		$posts = $this->get('input')->post();

		if ($posts) {
			try {
				$this->validate('group', $posts);

				//insert group
				$data = $this->createGroupsInsertData($posts);
				$id = $this->model('@user\UsersGroup')->insert($data);

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
			'permissionIds' => array(),
			'id' => 'group-create',
			'title' => __('Add Group')
		]);

		$permissions = $this->model('@user\UsersGroup')->getPermission()->all();
		set(['permissions' => $permissions ]);

		return $this->render('@user/admin/group/edit', $this->getData());
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
		$this->model('@user\UsersGroup')->clearPermissions($id);

		foreach ($permissions as $permission) {
			$this->model('@user\UsersGroup')->insertPermission($permission, $id);
		}
	}
}