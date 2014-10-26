<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BaseController;

class System extends BaseController {

	public function log()
	{
		$action = $this->get('input')->post('action');
		$logIds = $this->get('input')->post('log');

		switch($action) {
			case "delete":
				if($logIds) {
					foreach ($logIds as $id) {
						$this->model('@system\Log')->delete($id);
					}
					message('Logs deleted !', 'success');
				}
				break;
			case "clear":
				$this->model('@system\Log')->clear();
				message('Logs cleared !', 'success');
			default:
				break;
		}

		$logs = $this->model('@system\Log')->all();

		set('title', __('Logs'));
		set('id', 'log');

		$tableHead = array(
			['field' => 'time', 'width' => '20%', 'label' => 'Time', 'format' => function($val, $item) {
				return date('d-m-Y H:i:s', $val);
			}],
			['field' => 'message', 'label' => 'Message', 'format' => function($val, $item){
				$name = $item->user_name;

				if($item->user_id == $this->get('session')->get('user.id')) {
					$name = __('You');
				}

				$userUrl = admin_url('user/edit/'.$item->user_id);

				return '<a href="'.$userUrl.'">'.$name.'</a> '.$item->message;
			}]
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

		$caches = $model->getAll();

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

	/**
	 * /search Controller
	 */
	public function search()
	{
		$q = $this->get('input')->get('q');

		$model = $this->model('@system\Search');

		$extensions = $this->get('app')->getExtensions();

		$queries = array();
		foreach ($extensions as $extension) {
			if(method_exists($extension, 'getSearchQuery')) {
				list($name, $query) = $extension->getSearchQuery();
				$queries[$name] = $query;
			}
		}

		$results = $model->doSearch($q, $queries);

		foreach ($results as &$result) {

			$data['results'] = $result['results'];
			$result['content'] = $this->get('twig')->render($result['name'].'/search.html', $data);
		}

		$data['results'] = $results;
		return $this->get('twig')->render('search.html', $data);
	}
}