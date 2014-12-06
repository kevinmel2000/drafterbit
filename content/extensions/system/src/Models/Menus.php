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
			->fetchAllObjects();

		return $menus;
	}
}