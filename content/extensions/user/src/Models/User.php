<?php namespace Drafterbit\Extensions\User\Models;

class User extends \Drafterbit\Framework\Model {

	public function queryAll($filters)
	{
		$query = $this->withQueryBuilder()->select('*') ->from('#_users','u');

		$status = $filters['status'];

		if($status == 'banned') {
			$query->where('u.status = 0');
		} else if($status == 'active') {
			$query->where('u.status = 1');
		}

		return $query ->fetchAllObjects();
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->withQueryBuilder()->select('*')->from('#_users', 'u');

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

		$users = $queryBuilder->fetchAllObjects();

		if($singleRequested) {
			return reset($users);
		}

		return $users;
	}

	public function getByEmail($email)
	{
		return $this->getBy('email', $email, true);
	}


	public function getSingleBy($key, $value = null)
	{
		return $this->getBy($key, $value, true);
	}
	
	public function update($data, $where)
	{
		return $this->get('db')->update('#_users', $data, $where);
	}

	public function insert($data)
	{
		$this->get('db')->insert('#_users', $data);
		return $this->get('db')->lastInsertId();
	}

	public function delete($ids = array())
	{
		// @todo optimize this
		foreach ($ids as $id) {
			$this->get('db')->delete('#_users_groups', ['user_id'=> $id]);
			$this->get('db')->delete('#_users', ['id' => $id]);
		}
	}

	public function clearGroups($id)
	{
		return $this->get('db')
			->delete('#_users_groups', array('user_id' => $id));
	}

	public function insertGroup($groupId, $userId) {
		
		$data['group_id'] = $groupId;
		$data['user_id'] = $userId;

		return $this->get('db')
			->insert('#_users_groups', $data);
	}

	public function getGroupIds($id)
	{
		$queryBuilder = $this->withQueryBuilder()
		->select('g.id')
		->from('#_groups', 'g')
		->join('g', '#_users_groups', 'ug', 'ug.group_id = g.id')
		->where('ug.user_id = :user_id')
		->setParameter(':user_id', $id);

		$groups = $queryBuilder->fetchAllObjects();

		$ids = array();
		foreach ($groups as $group) {
			$ids[] = $group->id;
		}

		return $ids;
	}

	/**
	 * Get user Permission by given user id
	 *
	 * @param int $userId
	 * @return array
	 */
	public function getPermissions($userId)
	{
		$queryBuilder = $this->withQueryBuilder()
		->select('pms.slug')
		->from('#_permissions', 'pms')
		->join('pms', '#_groups_permissions', 'gp', 'gp.permission_id = pms.id')
		->join('gp', '#_groups', 'g', 'gp.group_id = g.id')
		->join('gp', '#_users_groups', 'ug','gp.group_id = ug.group_id')
		->where('ug.user_id = :user_id')
		->setParameter(':user_id', $userId);

		$permissions = $queryBuilder->fetchAllObjects();

		$returned = array();
		foreach($permissions as $pms) {
			$returned[] = $pms->slug;
		}

		return $returned;
	}
}