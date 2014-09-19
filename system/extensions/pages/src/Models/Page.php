<?php namespace Drafterbit\Extensions\Pages\Models;

class Page extends \Drafterbit\Framework\Model {

	public function all()
	{
		return
		$this->get('db')->createQueryBuilder()
		->select('*')
		->from('pages','p')
		->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function insert($data)
	{
		$this->get('db')->insert('pages', $data);
		return $this->get('db')->lastInsertId();
	}

	public function update($data, $id)
	{
		return
		$this->get('db')->update('pages', $data, array('id' => $id));
	}

	public function delete($id)
	{
		$this->get('db')->delete("pages", ['id'=> $id]);
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('pages', 'p');

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