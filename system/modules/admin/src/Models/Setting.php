<?php namespace Drafterbit\Modules\Admin\Models;

class Setting extends \Partitur\Model {

	public function all()
	{
		$queryBuilder = $this->get('db')->createQueryBuilder();

		$sets = $queryBuilder
		->select('st.*')
		->from('settings','st')
		->execute()->fetchAll(\PDO::FETCH_CLASS);

		$array = array();
		foreach ($sets as $set) {
			$array[$set->name] = $set->value;
		}

		return $array;
	}

	public function updateSetting($data)
	{
		foreach ($data as $key => $value) {

			$qb = $this->get('db')->createQueryBuilder();
			$qb->update( 'settings', 'st');
			$qb->set('value',':value');
			$qb->where('name=:key');
			$qb->setParameter(':key', $key);
			$qb->setParameter(':value', $value);
			$qb->execute();
		}
	}

	public function updateTheme($theme)
	{
		return $this->updateSetting(array('theme' => $theme));
	}
}