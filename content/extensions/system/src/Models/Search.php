<?php namespace Drafterbit\Extensions\System\Models;

class Search extends \Drafterbit\Framework\Model {

	public function doSearch($q, $queries)
	{
		$results = array();
		
		if($q) {		
			foreach ($queries as $name => $query) {

				$query->setParameter(':q', "%$q%");
				$res = $query->fetchAllObjects();
				
				if($res) {				
					$data = array();
					$data['name'] = $name;
					$data['results'] = $res;
					$results[] = $data;
				}
			}
		}

		return $results;
	}
}