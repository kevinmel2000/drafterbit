<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\System\BaseController;
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

		set('title', __('Logs'));
		set('id', 'log');

		$tableHead = array(
			['field' => 'time', 'label' => 'Time'],
			['field' => 'message', 'label' => 'Message']
		);
		set('logTable', $this->datatables('log', $tableHead, $logs));

		return $this->render('@system/admin/log', $this->getData());
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

		$tableHead = array(
			['field' => 'name', 'label' => 'Name'],
			['field' => 'size', 'label' => 'Filesize']
		);

		$cacheTable = $this->datatables('cache', $tableHead, $caches);

		set('id','cache');
		set('title',__('Cache'));
		set('cacheTable', $cacheTable);

		return $this->render('@system/admin/cache', $this->getData());
	}

	public function info()
	{
		return view();
	}
}