<?php namespace Drafterbit\Extensions\User\Models;

class Permission extends \Drafterbit\Framework\Model {

	public function all()
	{
		$stmt = $this->get('db')->createQueryBuilder()->select('*')->from('permissions', 'pms');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}
}