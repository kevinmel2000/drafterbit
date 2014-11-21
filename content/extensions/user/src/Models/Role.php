<?php namespace Drafterbit\Extensions\User\Models;

class Role extends \Drafterbit\Framework\Model {

	protected $permission;

	public function __construct( Permission $permission )
	{
		$this->permission = $permission;
	}

	public function queryAll()
	{
		return $this->get('db')
			->fetchAllObjects('SELECT * from #_roles');
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$q = $this->withQueryBuilder()->select('*')->from('#_roles', 'u');

		if (is_array($key)) {
		
			foreach ($key as $k => $v) {
	            $holder = ":$k";
	            $q->where("$k = $holder")
	               ->setParameter($holder, $v);
        	}
		
		} else {
			
			$q->where("$key = :$key")
			->setParameter(":$key", $value);
		}

		$groups = $q->fetchAllObjects();

		if($singleRequested) {
			return reset($groups);
		}

		return $groups;
	}

	public function getSingleBy($key, $value = array())
	{
		return $this->getBy($key, $value, true);
	}

	public function getRoleName($id)
	{
		$role = $this->getSingleBy('id', $id);
		return $role->label;
	}

	public function getByUser($id)
	{
		$this->withQueryBuilder()
		->select('*')
		->from('#_roles', 'g')
		->innerJoin('g', '#_users_roles', 'ug', 'g.id = ug.group_id')
		->where("ug.user_id = :user_id")
		->setParameter(':user_id', $id)
		->fetchAllObjects();
	}

	public function getPermission()
	{
		return $this->permission;
	}

	public function update($data, array $where)
	{
		return $this->get('db')
			->update('#_roles', $data, $where);
	}

	public function delete($ids = array())
	{
		$ids = (array) $ids;
		$ids = array_map(function($v){return "'$v'";}, $ids);
		$idString = implode(',', $ids);

		$this->withQueryBuilder()
		->delete('#_roles_permissions')
		->where('group_id IN ('.$idString.')')
			->execute();
		
		$this->withQueryBuilder()
		->delete('#_roles')
		->where('id IN ('.$idString.')')
			->execute();
	}

	public function getRoledUsers($id)
	{
		return
		$this->withQueryBuilder()
			->select('*')
			->from('#_users_roles', 'ug')
			->where("ug.group_id = $id")
			->fetchAllObjects();
	}

	public function insert($data)
	{
		$this->get('db')
			->insert('#_roles', $data);
		return $this->get('db')->lastInsertId();
	}

	public function isExists($id)
	{
		$roles = $this->getBy('id', $id);
		return count($roles) > 0;
	}

	public function save($id, $data)
	{
		if($this->isExists($id)) {
			$this->update($data, ['id' => $id]);
		} else {
			$id = $this->insert($data);
		}

		return $id;
	}

	public function insertPermission($permission, $id)
	{
		$data = array();
		$data['permission_id'] = $permission;
		$data['group_id'] = $id;

		return
		$this->get('db')->insert('#_roles_permissions', $data);
	}

	public function clearPermissions($id)
	{
		return $this->get('db')
              ->delete('#_roles_permissions', array('group_id' => $id));
	}

	public function getPermissionIds($id)
	{
		$pmss =  $this->withQueryBuilder()
		->select('pms.id')
		->from('#_permissions', 'pms')
		->join('pms', '#_roles_permissions', 'gp', 'pms.id = gp.permission_id')
		->where('gp.group_id = :group_id')
		->setParameter(':group_id', $id)
		->fetchAllObjects();

		$ids = array();
		foreach( $pmss as $pms ) {
			$ids[] = $pms->id;
		}

		return $ids;
	}

	/**
	 * Simply get all permissions
	 *
	 * @return array
	 */
	public function getPermissions()
	{
		$pmss =  $this->withQueryBuilder()
		->select('pms.slug, pms.label')
		->from('#_permissions', 'pms')
		->fetchAllObjects();

		$returned = array();

		foreach ($pmss as $pms) {
			$returned[$pms->slug] = $pms->label;
		}
		
		return $returned;
	}
}