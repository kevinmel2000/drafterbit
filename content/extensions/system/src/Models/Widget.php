<?php namespace Drafterbit\Extensions\System\Models;

use Drafterbit\Framework\Model;

class Widget extends Model {

	public function widget($position)
	{
		$qb = $this->get('db')->createQueryBuilder();
		
		$widgets = $qb->select('*')
			->from('#_widgets','w')
			->where('position=:position')
			->setParameter('position', $position)
			->execute()->fetchAll();
		
		foreach ($widgets as &$widget) {
			$widget['data'] = json_decode($widget['data'], true);
		}
		
		return $widgets;
	}

	public function add($name, $pos)
	{
		$theme = $this->get('themes')->current();

		$this->get('db')
		    ->insert('#_widgets',  array(
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
			->from('#_widgets','w')
			->where('id=:id')
			->setParameter('id', $id)
			->execute()->fetchAll();

		return reset($widget);
	}

	public function remove($id)
	{
		return $this->get('db')->delete("#_widgets", ['id'=> $id]);
	}

	public function save($id, $title, $data, $name = null, $position = null, $theme = null)
	{
		$data =  json_encode($data);

		if($this->has($id)) {
			$this->get('db')->update('#_widgets', array('title' => $title, 'data' => $data), array('id' => $id));
			return $id;
		}

		$data = array(
			'position' => $position,
			'title' => $title,
			'data' => $data,
			'name' => $name,
			'position' => $position,
			'theme' => $theme
		);
		$this->get('db')->insert('#_widgets', $data);
		return $this->get('db')->lastInsertId();
	}

	public function has($id)
	{
		return (bool) $this->fetch($id);
	}
}