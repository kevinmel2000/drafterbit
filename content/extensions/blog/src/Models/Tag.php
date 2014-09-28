<?php namespace Drafterbit\Blog\Models;

class Tag extends \Drafterbit\Framework\Model {

	public function all()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		return
		$queryBuilder
		->select('*')
		->from('#_tags','t')
		->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getBy($key, $value = null, $singleRequested=false)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$stmt = $queryBuilder->select('*')->from('#_tags', 't');

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

		$tags = $stmt->execute()->fetchAll(\PDO::FETCH_CLASS);

		if($singleRequested) {
			return reset($tags);
		}

		return $tags;
	}

	public function getByPost($id)
	{
		return
		$queryBuilder = $this->get('db')->createQueryBuilder();
		$queryBuilder
		->select('t.label, t.slug')
		->from('#_tags', 't')
		->innerJoin('t', '#_posts_tags', 'pt', 't.id = pt.tag_id')
		->where("pt.post_id = :post_id")
		->setParameter(':post_id', $id)
		->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getIdBy($field, $value)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		
		$tag = $queryBuilder
		->select('*')
		->from('#_tags', 't')
		->where("$field = '$value'")
		->execute()->fetchObject();

		if(!isset($tag->id)) {
			return false;
		}

		return $tag->id;
	}

	public function save($tag)
	{
		$data['label'] = $tag;
		$data['slug'] = slug($tag);
		$this->get('db')->insert('#_tags', $data);

		return $this->get('db')->lastInsertId();
	}
}