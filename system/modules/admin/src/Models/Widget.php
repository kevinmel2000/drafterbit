<?php namespace Drafterbit\Modules\Admin\Models;

use Drafterbit\Framework\Model;

class Widget extends Model {

	public function widget($position)
	{
		$qb = $this->get('db')->createQueryBuilder();
		
		$widgets = $qb->select('*')
			->from('widgets','w')
			->where('position=:position')
			->setParameter('position', $position)
			->execute()->fetchAll(\PDO::FETCH_CLASS);

		return $widgets;
	}

	public function add($name, $pos)
	{
		$theme = $this->get('themes')->current();

		$this->get('db')
		    ->insert('widgets',  array(
		            'name' => $name,
		            'position' =>  $pos,
		            'theme' =>  $theme
		        ));

		return $this->get('db')->lastInsertId();
	}

	public function fetch($id)
	{
		$qb = $this->get('db')->createQueryBuilder();
		
		$widget = $qb->select('*')
			->from('widgets','w')
			->where('id=:id')
			->setParameter('id', $id)
			->execute()->fetchAll(\PDO::FETCH_CLASS);

		return reset($widget);
	}

	public function remove($id)
	{
		return $this->get('db')->delete("widgets", ['id'=> $id]);
	}

	public function save($id, $title, $data, $name = null, $position = null, $theme = null)
	{
		$data =  json_encode($data);

		if($this->has($id)) {
			return $this->get('db')->update('widgets', array('title' => $title, 'data' => $data), array('id' => $id));
		}

		$data = array(
			'position' => $position,
			'title' => $title,
			'data' => $data,
			'name' => $name,
			'position' => $position,
			'theme' => $theme
		);
		return $this->get('db')->insert('widgets', $data);
	}

	public function has($id)
	{
		return (bool) $this->fetch($id);
	}
}