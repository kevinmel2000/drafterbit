<?php namespace Drafterbit\User\Models;

class UsersGroup extends \Partitur\Model {

	protected $permission;

	public function __construct( Permission $permission )
	{
		$this->permission = $permission;
	}

	public function all()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('groups', 'g');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('groups', 'u');

		if (is_array($key)) {
		
			foreach ($key as $k => $v) {
	            $holder = ":$k";
	            $queryBuilder->where("$k = $holder")
	               ->setParameter($holder, $v);
        	}
		
		} else {
			
			$queryBuilder->where("$key = :$key")
			->setParameter(":$key", $value);
		}

		$groups = $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);

		if($singleRequested) {
			return reset($groups);
		}

		return $groups;
	}

	public function getSingleBy($key, $value = array())
	{
		return $this->getBy($key, $value, true);
	}


	public function getByUser($id)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$queryBuilder
		->select('*')
		->from('groups', 'g')
		->innerJoin('g', 'users_groups', 'ug', 'g.id = ug.group_id')
		->where("ug.user_id = :user_id")
		->setParameter(':user_id', $id);

		return $queryBuilder->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getPermission()
	{
		return $this->permission;
	}

	public function update($data, array $where)
	{
		return $this->get('db')
			->update('groups', $data, $where);
	}

	public function delete($id)
	{
		$this->get('db')
			->delete('groups_permissions', ['group_id' => $id]);
		$this->get('db')
			->delete('users_groups', ['group_id'=> $id]);
		$this->get('db')
			->delete('groups', ['id' => $id]);
	}

	public function insert($data)
	{
		$this->get('db')
			->insert('groups', $data);
		return
		$this->get('db')->lastInsertId();
	}

	public function insertPermission($permission, $id)
	{
		$data = array();
		$data['permission_id'] = $permission;
		$data['group_id'] = $id;

		return
		$this->get('db')->insert('groups_permissions', $data);
	}

	public function clearPermissions($id)
	{
		return $this->get('db')
              ->delete('groups_permissions', array('group_id' => $id));
	}

	public function getPermissionIds($id)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$pmss =  $queryBuilder
		->select('pms.id')
		->from('permissions', 'pms')
		->join('pms', 'groups_permissions', 'gp', 'pms.id = gp.permission_id')
		->where('gp.group_id = :group_id')
		->setParameter(':group_id', $id)
		->execute()->fetchAll();

		$ids = array();

		foreach( $pmss as $pms ) {
			$ids[] = $pms['id'];
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
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$pmss =  $queryBuilder
		->select('pms.key, pms.label')
		->from('permissions', 'pms')
		->execute()->fetchAll();

		$returned = array();

		foreach ($pmss as $pms) {
			$returned[$pms['key']] = $pms['label'];
		}
		
		return $returned;
	}
}