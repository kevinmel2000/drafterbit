<?php namespace Drafterbit\Modules\User\Models;

class User extends \Drafterbit\Framework\Model {

	public function all()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('users', 'u');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('users', 'u');

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

		$users = $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);

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
		return $this->get('db')->update('users', $data, $where);
	}

	public function insert($data)
	{
		$this->get('db')->insert('users', $data);
		return $this->get('db')->lastInsertId();
	}

	public function delete($id)
	{
		$this->get('db')->delete('users_groups', ['user_id'=> $id]);
		$this->get('db')->delete('users', ['id' => $id]);
	}

	public function clearGroups($id)
	{
		return
		$this->get('db')->delete('users_groups', array('user_id' => $id));
	}

	public function insertGroup($groupId, $userId) {
		
		$data = array();
		$data['group_id'] = $groupId;
		$data['user_id'] = $userId;

		return
		$this->get('db')->insert('users_groups', $data);
	}

	public function getGroupIds($id)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder
		->select('g.id')
		->from('groups', 'g')
		->join('g', 'users_groups', 'ug', 'ug.group_id = g.id')
		->where('ug.user_id = :user_id')
		->setParameter(':user_id', $id);

		$groups = $stmt->execute()->fetchAll();

		$ids = array();
		foreach ($groups as $group) {
			$ids[] = $group['id'];
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
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('pms.key')
		->from('permissions', 'pms')
		->join('pms', 'groups_permissions', 'gp', 'gp.permission_id = pms.id')
		->join('gp', 'groups', 'g', 'gp.group_id = g.id')
		->join('gp', 'users_groups', 'ug','gp.group_id = ug.group_id')
		->where('ug.user_id = :user_id')
		->setParameter(':user_id', $userId);

		$permissions = $stmt->execute()->fetchAll();

		$returned = array();
		foreach($permissions as $pms) {
			$returned[] = $pms['key'];
		}

		return $returned;
	}
}