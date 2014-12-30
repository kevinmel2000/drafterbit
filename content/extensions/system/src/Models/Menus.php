<?php namespace Drafterbit\Extensions\System\Models;

use Drafterbit\Framework\Model;

class Menus extends Model {

	public function getByThemePosition($theme, $position)
	{		
		$menus = $this->withQueryBuilder()
			->select('*')
			->from('#_menus','w')
			->where('position=:position')
			->andWhere('theme=:theme')
			->setParameter('position', $position)
			->setParameter('theme', $theme)
			->getResult();

		return $menus;
	}

	public function save($id, $data)
	{
		if($this->exists($id)) {
			return $this->update($id, $data);
		} else {
			return $this->insert($data);
		}
	}

	/**
	 * Check if a menu is exists
	 */
	public function exists($id)
	{
		return (bool)
		$this->withQueryBuilder()
			->select('*')
			->from('#_menus', 'm')
			->where("id = $id")
			->getResult();
	}

	public function update($id, $data)
	{
		$this->get('db')->update('#_menus', $data, array('id' => $id));
		return $id;
	}

	public function insert($data)
	{
		$this->get('db')->insert('#_menus', $data);
		return $this->get('db')->lastInsertId();
	}
}