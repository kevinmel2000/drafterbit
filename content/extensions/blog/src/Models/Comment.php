<?php namespace Drafterbit\Blog\Models;

use Drafterbit\Framework\Model;

class Comment extends Model {

	public function queryAll($status = null)
	{
		$query = $this ->withQueryBuilder()
		->select('c.*, p.title')
		->from('#_comments','c')
		->where('c.status != 2') //spam
		->andWhere("c.deleted_at = '0000-00-00 00:00:00'") //trashed
		->leftJoin('c','#_posts','p', 'c.post_id = p.id')
		->orderBy('c.created_at', 'desc');

		return $query->fetchAllObjects();
	}

	public function getByPostId($id)
	{
		$query = $this ->withQueryBuilder()
		->select('c.*')
		->from('#_comments','c')
		->where('c.post_id = :post_id')
		->andWhere('c.status = 1')
		->setParameter('post_id', $id);

		$comments =  $query->fetchAllObjects();

		// group parent and childs
		$childs = array();
		$parents = array();	
		foreach ($comments as $comment) {
			if($comment->parent_id != 0) {
				$childs[$comment->parent_id][] = $comment;
			} else {
				$parents[] = $comment;
			}
		}
		unset($comments);

		foreach ($parents as &$parent) {
			$parent->childs = $this->getChilds($parent->id, $childs);
		}
		unset($childs);

		return $parents;
	}

	private function getChilds($id, $c)
	{
		$childs = isset($c[$id]) ? $c[$id] : array();
		foreach ($childs as &$child) {
			$child->childs = $this->getChilds($child->id, $c);
		}

		return $childs;
	}

	public function insert($data)
	{
		$this->get('db')->insert('#_comments', $data);

		return $this->get('db')->lastInsertid();
	}

	public function update($id, $data) {
		$this->get('db')->update('#_comments', $data, array('id' => $id));
	}

	public function changeStatus($id, $status)
	{
		$this->update($id, ['status' => $status]);
	}

	public function trash($id)
	{
		$this->update($id, ['deleted_at' => \Carbon\Carbon::now()]);
	}
}