<?php namespace Drafterbit\Modules\Admin\Controllers;

use Drafterbit\Modules\Admin\BaseController;
use Drafterbit\Modules\User\Models\Auth;
use Drafterbit\Modules\Admin\Models\Setting as SettingModel;

class Setting extends BaseController {

	protected $setting;

	public function __construct(Auth $auth, SettingModel $setting)
	{
		parent::__construct($auth);
		$this->setting = $setting;
	}

	public function general()
	{
		$this->auth->restrict('setting.change');

		$post = $this->get('input')->post();

		if ($post) {

			// @todo validate setting

			$data = $this->setupData($post);
			$this->setting->updateSetting($data);

			message('Setting updated !', 'success');
		}
		
		$config = $this->get('cache')->fetch('settings');
		
		set([
			'siteName' => $config['site.name'],
			'tagLine' => $config['site.tagline'],
			'address' => $config['site.address'],
			'offline' => $config['offline'],
			'offlineMessage' => $config['offline.message'],
			'adminEmail' => $config['email'],
			'language' => $config['language'],
			'timezone' => $config['timezone'],
			'dateFormat' => $config['format.date'],
			'timeFormat' => $config['format.time'],
		]);

		$ui = $this->model('UI@admin');

		$header =  $ui->header('General Setting', 'General setting');

		$tbConfig = array(

			'save' => array(
				'type' => 'submit.success',
				'label' => 'Update',
				'name' => 'action',
				'value' => 'update',
				'faClass' => 'fa-check'
			),

		);

		$toolbar = $ui->toolbar($tbConfig);

		$view = $this->get('template')->render('setting/general@admin', $this->getData());

		$form = $ui->form(null, $toolbar, $view);

		$content = $header.$form;

		return $this->wrap($content);
	}

	public function themes()
	{
		$cache = $this->get('cache');
		$post = $this->get('input')->post();

		if($post) {
			$this->setting->updateTheme($post['theme']);

			$cache->delete('settings');
			
			message('Theme updated', 'success');

			//$msg['text'] = 'Theme updated successfully';
			//$msg['type'] = 'success';
			//$this->get('session')
			//	->getFlashBag()->set('message', $msg);
			//return redirect(base_url("admin/setting/themes"));
		}

		if(!$cache->contains('settings')) {
			$cache->save('settings', $this->setting->all());
		}

		// @todo
		$settings = $cache->fetch('settings');
		set('currentTheme', $settings['theme']);

		$themesDir = $this->get('path.themes');
		$themesDetected = array_diff( scandir( $themesDir ), Array( ".", ".." ) );

		$themes = array();
		foreach ($themesDetected as $dir) {

			if( is_file($path = $themesDir.$dir.'/config.yml')) {
				$themes[$dir] = $this->get('yaml')->parse(file_get_contents($path));
			} else {
				continue;
			}
		}

		set('themes', $themes);

		$ui = $this->model('UI@admin');

		$header =  $ui->header('Themes', 'Appearance setting');

		$view = $this->get('template')->render('setting/appearance@admin', $this->getData());

		$form = $ui->form(null, null, $view);

		$content = $header.$form;

		return $this->wrap($content);
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
		$data['site.address'] = $p['site-address'];	
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

		return $data;
	}
}