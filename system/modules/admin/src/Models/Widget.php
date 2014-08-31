<?php namespace Drafterbit\Modules\Admin\Models;

use Partitur\Model;

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

	public function remove($id)
	{
		return $this->get('db')->delete("widgets", ['id'=> $id]);
	}
}