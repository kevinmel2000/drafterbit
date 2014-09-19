<?php namespace Drafterbit\CMS\System;

abstract class Controller extends \Drafterbit\Framework\Controller {
    
	function view()
	{
        $fileName = $this->get('asset')->writeCSS();
		$jsFileName = $this->get('asset')->writeJs();
		
		$this->data['stylesheet'] = base_url('content/cache/asset/css/'.$fileName.'.css');
		$this->data['script'] = base_url('content/cache/asset/js/'.$jsFileName.'.js');
		
        return parent::view();
	}
}