<?php namespace Drafterbit\Blog\Models;

class Post extends \Drafterbit\Framework\Model {

	public function all($status = null)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();

		$queryBuilder
		->select('p.*, u.email as authorEmail, u.real_name as authorName')
		->from('#_posts','p')
		->leftJoin('p','#_users','u', 'p.user_id = u.id');

		if($status) {
			$queryBuilder
			->where('p.status=:status')
			->setParameter('status', $status);
		}

		return $queryBuilder->fetchAllObjects();
	}

	public function insert($data)
	{
		$this->get('db')->insert('#_posts', $data);
		return $this->get('db')->lastInsertId();
	}

	public function update($data, $id)
	{
		return
		$this->get('db')->update('#_posts', $data, array('id' => $id));
	}

	public function delete($id)
	{
		$this->get('db')->delete("#_posts_tags", ['post_id'=> $id]);
		$this->get('db')->delete("#_posts", ['id'=> $id]);
	}

	public function getBy($field, $value)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		
		return
		$queryBuilder->select('*')->from('#_posts', 'p')
		->where("$field = '$value'")
		->execute()->fetchObject();
	}

	public function clearTag($id)
	{
		return
		$this->get('db')
		->delete('#_posts_tags', array("post_id" => $id));
	}

	public function addTag($tagId, $id)
	{
		$data['post_id'] = $id;
		$data['tag_id'] = $tagId;
		return
		$this->get('db')->insert('#_posts_tags', $data);
	}

	public function getTags($id)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();

		return
		$queryBuilder
		->select('t.label, t.slug')
		->from('#_tags', 't')
		->innerJoin('t', '#_posts_tags', 'pt', 't.id = pt.tag_id')
		->where("pt.post_id = '$id'")
		->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getSingleBy($field, $value)
	{
		return
		$this->get('db')->createQueryBuilder()
		->select('*')
		->from('#_posts', 'p')
		->where("$field = :value")
		->setParameter(':value', $value)
		->execute()->fetchObject();
	}
}