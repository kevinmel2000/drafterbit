<?php namespace Drafterbit\User\Models;

class Permission extends \Partitur\Model {

	public function all()
	{
		$stmt = $this->get('db')->createQueryBuilder()->select('*')->from('permissions', 'pms');
		return $stmt->execute()->fetchAll(	\PDO::FETCH_CLASS);
	}
}