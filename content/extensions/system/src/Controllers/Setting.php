<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Setting extends BackendController {

	public function general()
	{
		// @todo
		//$this->model('@user\Auth')->restrict('setting.change');

		$post = $this->get('input')->post();

		if ($post) {

			// @todo validate setting

			$data = $this->setupData($post);
			$this->model('@system\System')->updateSetting($data);

			message('Setting updated !', 'success');
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
}