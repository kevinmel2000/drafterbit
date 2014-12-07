<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Setting extends BackendController {

	public function general()
	{
		$post = $this->get('input')->post();

		if ($post) {

			// @todo validate setting

			$data = $this->setupData($post);
			$this->model('@system\System')->updateSetting($data);
			$this->get('template')->addGlobal('messages', [['text' => "Setting updated", "type" => 'success']]);
		}
		
		$config = $this->model('@system\System')->all();
		
		set([
			'siteName' => $config['site.name'],
			'tagLine' => $config['site.description'],
			'adminEmail' => $config['email'],
			'language' => $config['language'],
			'timezone' => $config['timezone'],
			'dateFormat' => $config['format.date'],
			'timeFormat' => $config['format.time'],
			'homepage' => $config['homepage'],
			'pageOptions' => $this->get('app')->getFrontPageOption(),
			'title' => __('General Setting'),
			'id' => 'setting'
		]);

		return $this->render('@system/setting/general', $this->getData());
	}

	protected function setupData($p)
	{
		$data = array();

		$data['site_name'] = $p['site-name'];	
		$data['site.tagline'] = $p['site-tagline'];	
		$data['email'] = $p['email'];	
		$data['language'] = $p['language'];	
		$data['timezone'] = $p['timezone'];
		$data['format.date'] = $p['format-date'];
		$data['format.time'] = $p['format-time'];

		$data['homepage'] = $p['homepage'];

		return $data;
	}
}