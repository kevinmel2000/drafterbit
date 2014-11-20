<?php namespace Drafterbit\Extensions\User\Models;

class Permission extends \Drafterbit\Framework\Model {

	public function queryAll()
	{
		$stmt = $this->get('db')->createQueryBuilder()->select('*')->from('#_permissions', 'pms');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}
}