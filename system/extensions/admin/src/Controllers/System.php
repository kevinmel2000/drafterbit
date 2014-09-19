<?php namespace Drafterbit\Extensions\Admin\Controllers;

use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\Admin\BaseController;
use Drafterbit\Extensions\System\Models\Log;

class System extends BaseController {

	protected $log;

	public function __construct( Auth $auth, Log $log)
	{
		parent::__construct($auth);
		$this->log = $log;
	}

	public function log()
	{
		$action = $this->get('input')->post('action');
		$logIds = $this->get('input')->post('log');

		switch($action) {
			case "delete":
				if($logIds) {
					foreach ($logIds as $id) {
						$this->log->delete($id);
					}
					message('Logs deleted !', 'success');
				}
				break;
			case "clear":
				$this->log->clear();
				message('Logs cleared !', 'success');
			default:
				break;
		}

		$logs = $this->log->all();

		$this->
			get('asset')
				->css('@bootstrap_datatables_css')
				->js('@datatables_js')
				->js('@bootstrap_datatables_js')
				->js('@jquery_check_all')
				->js($this->assetPath('js/log.js'));

		$ui = $this->model('UI@admin');
		
		$tbConfig = array(
			'delete' => array(
				'type' => 'submit',
				'label' => 'Delete',
				'name'=> 'action',
				'value' => 'delete',
				'faClass' => false
			),
			'clear' => array(
				'type' => 'submit',
				'label' => 'Clear',
				'name'=> 'action',
				'value' => 'clear',
				'faClass' =>  false
			)
		);

		$tableConfig = array(
			['field' => 'time', 'label' => 'Time'],
			['field' => 'message', 'label' => 'Message']
		);

		$header =  $ui->header('Log', 'Activity logs');
		$table = $ui->datatables('log', $logs, $tableConfig);

		$toolbar = $ui->toolbar($tbConfig);

		$listFormed = $ui->listFormed(null, $toolbar, $table);

		$content = $header.$listFormed;

		return $this->wrap($content);
	}

	public function cache()
	{
		$post = $this->get('input')->post();

		if (isset($post['action']) and ($post['action'] == 'delete') and isset($post['cache'])) {

			foreach($post['cache'] as $key ) {
				$this->get('cache')->delete($key);
			}

			message('Cache deleted !', 'success');
		}

		$model = $this->model('cache');

		$caches = $model->all();

		//set('stat', $this->get('cache')->getStats());

		// @todo append asset from ui model automatically
		$this->
			get('asset')
				->css('@bootstrap_datatables_css')
				->js('@datatables_js')
				->js('@bootstrap_datatables_js')
				->js('@jquery_check_all')
				->js($this->assetPath('js/cache.js'));

		$ui = $this->model('UI@admin');
		
		$tbConfig = array(
			'delete' => array(
				'type' => 'submit',
				'label' => 'Delete',
				'name'=> 'action',
				'value' => 'delete',
				'faClass' =>  false
			)
		);

		$header =  $ui->header('Cache', 'Cache manager');
		$toolbar = $ui->toolbar($tbConfig);
		//$view = $this->get('template')->render('admin/cache@system', $this->getData());
		//$form = $ui->form(null, $toolbar, $view);

		$tableConfig = array(
			['field' => 'name', 'label' => 'Name'],
			['field' => 'size', 'label' => 'Filesize']
		);

		$table = $ui->datatables('cache', $caches, $tableConfig);
		$list = $ui->listFormed(null, $toolbar, $table);

		$content = $header.$list;

		return $this->wrap($content);
	}

	public function info()
	{
		return view();
	}
}