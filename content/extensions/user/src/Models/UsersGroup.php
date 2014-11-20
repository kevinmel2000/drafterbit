<?php namespace Drafterbit\Extensions\User\Models;

class UsersGroup extends \Drafterbit\Framework\Model {

	protected $permission;

	public function __construct( Permission $permission )
	{
		$this->permission = $permission;
	}

	public function queryAll()
	{
		return $this->get('db')
			->fetchAllObjects('SELECT * from #_groups');
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('#_groups', 'u');

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
		$this->withQueryBuilder()
		->select('*')
		->from('#_groups', 'g')
		->innerJoin('g', '#_users_groups', 'ug', 'g.id = ug.group_id')
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
			->update('#_groups', $data, $where);
	}

	public function delete($id)
	{
		$this->get('db')
			->delete('#_groups_permissions', ['group_id' => $id]);
		$this->get('db')
			->delete('#_users_groups', ['group_id'=> $id]);
		$this->get('db')
			->delete('#_groups', ['id' => $id]);
	}

	public function insert($data)
	{
		$this->get('db')
			->insert('#_groups', $data);
		return
		$this->get('db')->lastInsertId();
	}

	public function insertPermission($permission, $id)
	{
		$data = array();
		$data['permission_id'] = $permission;
		$data['group_id'] = $id;

		return
		$this->get('db')->insert('#_groups_permissions', $data);
	}

	public function clearPermissions($id)
	{
		return $this->get('db')
              ->delete('#_groups_permissions', array('group_id' => $id));
	}

	public function getPermissionIds($id)
	{
		$pmss =  $this->withQueryBuilder()
		->select('pms.id')
		->from('#_permissions', 'pms')
		->join('pms', '#_groups_permissions', 'gp', 'pms.id = gp.permission_id')
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