<?php namespace Drafterbit\Extensions\User\Models;

class Role extends \Drafterbit\Framework\Model {

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

		$roles = $q->fetchAllObjects();

		if($singleRequested) {
			return reset($roles);
		}

		return $roles;
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
		->from('#_roles', 'r')
		->innerJoin('r', '#_users_roles', 'ur', 'r.id = ur.role_id')
		->where("ur.user_id = :user_id")
		->setParameter(':user_id', $id)
		->fetchAllObjects();
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
		->delete('#_roles')
		->where('id IN ('.$idString.')')
			->execute();
	}

	public function getRoledUsers($id)
	{
		return
		$this->withQueryBuilder()
			->select('*')
			->from('#_users_roles', 'ur')
			->where("ur.role_id = $id")
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
}