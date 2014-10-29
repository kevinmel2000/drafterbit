<?php namespace Drafterbit\Blog\Models;

use Drafterbit\Framework\Model;

class Comment extends Model {

	public function queryAll($status = null)
	{
		$query = $this ->withQueryBuilder()
		->select('c.*, p.title')
		->from('#_comments','c')
		->leftJoin('c','#_posts','p', 'c.post_id = p.id');

		return $query->fetchAllObjects();
	}

	public function getByPostId($id)
	{
		$query = $this ->withQueryBuilder()
		->select('c.*')
		->from('#_comments','c')
		->where('c.post_id = :post_id')
		->where('c.status = 1')
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

}