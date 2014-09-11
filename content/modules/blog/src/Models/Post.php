<?php namespace Drafterbit\Blog\Models;

class Post extends \Drafterbit\Framework\Model {

	public function all($status = null)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();

		$queryBuilder
		->select('p.*, u.email as authorEmail, u.real_name as authorName')
		->from('posts','p')
		->leftJoin('p','users','u', 'p.user_id = u.id');

		if($status) {
			$queryBuilder
			->where('p.status=:status')
			->setParameter('status', $status);
		}

		return $queryBuilder->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function insert($data)
	{
		$this->get('db')->insert('posts', $data);
		return $this->get('db')->lastInsertId();
	}

	public function update($data, $id)
	{
		return
		$this->get('db')->update('posts', $data, array('id' => $id));
	}

	public function delete($id)
	{
		$this->get('db')->delete("posts_tags", ['post_id'=> $id]);
		$this->get('db')->delete("posts", ['id'=> $id]);
	}

	public function getBy($field, $value)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();
		
		return
		$queryBuilder->select('*')->from('posts', 'p')
		->where("$field = '$value'")
		->execute()->fetchObject();
	}

	public function clearTag($id)
	{
		return
		$this->get('db')
		->delete('posts_tags', array("post_id" => $id));
	}

	public function addTag($tagId, $id)
	{
		$data['post_id'] = $id;
		$data['tag_id'] = $tagId;
		return
		$this->get('db')->insert('posts_tags', $data);
	}

	public function getTags($id)
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();

		return
		$queryBuilder
		->select('t.label, t.slug')
		->from('tags', 't')
		->innerJoin('t', 'posts_tags', 'pt', 't.id = pt.tag_id')
		->where("pt.post_id = '$id'")
		->execute()->fetchAll(\PDO::FETCH_CLASS);
	}

	public function getSingleBy($field, $value)
	{
		return
		$this->get('db')->createQueryBuilder()
		->select('*')
		->from('posts', 'p')
		->where("$field = :value")
		->setParameter(':value', $value)
		->execute()->fetchObject();
	}
}