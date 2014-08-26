<?php namespace Drafterbit\Modules\Admin\Models;

use Partitur\Model;

class Widget extends Model {

	public function positions($theme)
	{
		$qb = $this->get('db')->createQueryBuilder();
		
		return
		$qb->select('*')
			->from('widgets','w')
			->where('theme=:theme')
			->setParameter('theme', $theme)
			->execute()->fetchAll(\PDO::FETCH_CLASS);
	}
}