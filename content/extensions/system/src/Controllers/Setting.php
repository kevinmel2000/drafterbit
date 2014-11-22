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
			'offline' => $config['offline'],
			'offlineMessage' => $config['offline.message'],
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

	public function costumizeTheme()
	{
		return $this->get('template.manager')->render('costumize@setting');
	}

	protected function setupData($p)
	{
		$data = array();

		$data['site_name'] = $p['site-name'];	
		$data['site.tagline'] = $p['site-tagline'];	
		//$data['site.address'] = $p['site-address'];	
		$data['email'] = $p['email'];	
		$data['language'] = $p['language'];	
		$data['timezone'] = $p['timezone'];
		$data['format.date'] = $p['format-date'];
		$data['format.time'] = $p['format-time'];

		if (isset($p['offline'])) {
			$data['offline'] = $p['offline'];
		} else {
			$data['offline'] = 0;
		}

		$data['offline.message'] = $p['offline-message'];
		$data['homepage'] = $p['homepage'];

		return $data;
	}

	public function themes()
	{
		$this->setting = $this->model('@system\System');

		$cache = $this->get('cache');
		$post = $this->get('input')->post();

		if($post) {
			$this->setting->updateTheme($post['theme']);

			$cache->delete('settings');
			
			message('Theme updated', 'success');
		}

		// @todo
		$settings = $this->setting->all();

		set('currentTheme', $settings['theme']);

		$themesDir = $this->get('path.themes');
		$themes = $this->get('themes')->all();

		set('themes', $themes);
		set('id', 'themes');
		set('title', __('Themes'));

		return $this->render('@system/setting/appearance', $this->getData());
	}
}