<?php namespace Drafterbit\System\Models;

class Log extends \Partitur\Model {

	public function all()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('logs', 'l')->orderBy('time', 'DESC');
		return $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function delete($id)
	{
		return $this->get('db')->delete('logs', ['id' => $id]);
	}

	public function clear()
	{
		return $this->get('db')->exec('TRUNCATE TABLE `logs`');
	}
}