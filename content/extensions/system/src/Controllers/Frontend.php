<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController {

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
		return $this->render('search.html', $data);
	}
}
