<?php namespace Drafterbit\Blog\Models;

use Drafterbit\Framework\Model;

class Comment extends Model {

	public function queryAll($filters)
	{
		$status = $filters['status'];

		$query = $this ->withQueryBuilder()
		->select('c.*, p.title')
		->from('#_comments','c')
		->leftJoin('c','#_posts','p', 'c.post_id = p.id')
		->orderBy('c.created_at', 'desc');

		if($status == 'spam') {
			$query->where('c.status = 2');
			$query->andWhere('c.deleted_at = :deleted_at');
			$query->setParameter(':deleted_at', '0000-00-00 00:00:00');
		} else if ($status == 'trashed') {
			$query->where('c.deleted_at != :deleted_at');
			$query->setParameter(':deleted_at', '0000-00-00 00:00:00');

		} else {

			$query->where("c.deleted_at = '0000-00-00 00:00:00'");

			if($status == 'approved'){
				$query->andWhere('c.status = 1');
			} else if($status == 'pending') {
				$query->andWhere('c.status = 0');
			} else {
				$query->andWhere('c.status != 2');
			}
		}

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

	/**
	 * Restore trashed pages
	 *
	 * @return void
	 */
	public function restore($ids)
	{
		$ids = array_map(function($v){return "'$v'";}, $ids);

		$idString = implode(',', $ids);
		$deleted_at = new \Carbon\Carbon;

		$this->withQueryBuilder()
			->update('#_comments', 'c')
			->set('c.deleted_at',"'0000-00-00 00:00:00'")
			->where('c.id IN ('.$idString.')')
			->execute();
	}

	public function delete($ids)
	{
		$ids = (array) $ids;
		unset($ids['all']);

		$ids = array_map(function($v){return "'$v'";}, $ids);
		$idString = implode(',', $ids);

		$q = $this->withQueryBuilder()
		->delete('#_comments')
		->where('id IN ('.$idString.')')
		->orWhere('parent_id IN ('.$idString.')');
		
		$q->execute();
	}
}