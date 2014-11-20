<?php namespace Drafterbit\Extensions\System\Models;

class Log extends \Drafterbit\Framework\Model {

	public function queryAll()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder
			->select('l.*, u.real_name as user_name')
			->from('#_logs', 'l')
			->leftJoin('l','#_users','u', 'l.user_id = u.id')
			->orderBy('time', 'DESC');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function recent()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder
			->select('l.*, u.real_name as user_name')
			->from('#_logs', 'l')
			->leftJoin('l','#_users','u', 'l.user_id = u.id')
			->orderBy('time', 'DESC')->setMaxResults(10);

		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);	
	}

	public function delete($id)
	{
		return $this->get('db')->delete('#_logs', ['id' => $id]);
	}

	public function clear()
	{
		return $this->get('db')->exec('TRUNCATE TABLE #_logs');
	}
}