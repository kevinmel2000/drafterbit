<?php namespace Drafterbit\Extensions\Pages\Models;

class Pages extends \Drafterbit\Framework\Model {

	public function all($status = 'untrashed', $q = null)
	{
		if($this->get('debug')) {
			return $this->doGetAll($status, $q);
		}

		$cache = $this->get('cache');
		if( ! $cache->contains('pages.'.$status)) {
			$cache->save('pages.'.$status, $this->doGetAll($status,$q));
		}

		return $cache->fetch('pages.'.$status);
	}


	private function doGetAll($status, $q = null)
	{
		$query = $this->withQueryBuilder() ->select('*') ->from('#_pages','p');

		if($status == 'untrashed') {
			$query->where('p.deleted_at = :deleted_at');
			$query->setParameter(':deleted_at', '0000-00-00 00:00:00');
		} else if($status == 'trashed') {
			$query->where('p.deleted_at != :deleted_at');
			$query->setParameter(':deleted_at', '0000-00-00 00:00:00');
		} else {
			$query->where('p.status = :status');
			$s = $status == 'published' ? 1 : 0;
			$query->setParameter(':status', $s);
		}

		if(!is_null($q) and $q !== '') {
			$query->andWhere("p.title LIKE '%".$q."%'");
		}

		return $query->fetchAllObjects();
	}



	public function insert($data)
	{
		$this->get('db')->insert('#_pages', $data);
		return $this->get('db')->lastInsertId();
	}

	public function update($data, $id)
	{
		return
		$this->get('db')->update('#_pages', $data, array('id' => $id));
	}

	public function delete($id)
	{
		$this->get('db')->delete("#_pages", ['id'=> $id]);
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('#_pages', 'p');

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

		$pages = $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);

		if($singleRequested) {
			return reset($pages);
		}

		return $pages;
	}

	public function getSingleBy($key, $value = null)
	{
		return $this->getBy($key, $value, true);
	}
}