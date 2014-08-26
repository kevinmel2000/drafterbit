<?php namespace Drafterbit\Modules\Support;

abstract class Controller extends \Partitur\Controller {

	/**
     * Data.
     *
     * @var array
     */
    public $data = array();

	function view()
	{
        $fileName = $this->get('asset')->writeCSS();
		$jsFileName = $this->get('asset')->writeJs();
		
		$this->data['stylesheet'] = base_url('content/cache/asset/css/'.$fileName.'.css');
		$this->data['script'] = base_url('content/cache/asset/js/'.$jsFileName.'.js');
		
        return $this->render($this->getTemplate(), $this->data);
	}

	public function assetPath($path)
	{
		return $this->module()->getResourcesPath().'public/'.$path;
	}

	/**
     * Get data;
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Render Template.
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, $data = array())
    {   
        if(strpos($template, '@') === false) {
            $path = $this->module()->getTemplatePath();

            if(file_exists($path.rtrim($template,'.php').'.php')) {
                $template = $template.'@'.$this->module()->name();
            }
        }
        
        return $this->get('template')->render($template, $data);
    }

    /**
     * Validation
     *
     * @param array $data
     * @param string
     */
    public function validate($ruleKey, $data)
    {
        $validator = $this->get('validation.form');

        $rules = $this->get('config')
                    ->get('validation.'.$ruleKey.'@'.$this->module()->name());

        $validator
            ->setRules($rules)
            ->validate($data);

        return $validator;
    }

    /**
     * Set data.
     *
     * @param string|array $key
     * @param mixed $value
     */
    public function set($key, $value = null)
    {
        if(is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            return $this->data[$key] = $value;
        }
    }

    /**
     * Set Template
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        return $this->template = $template;
    }

    /**
     * Get Template()
     */
    public function getTemplate()
    {
        if( ! empty($this->template)) {
            return $this->template;
        }

        $action = snake_case($this->get('current.action'), '-');
        return $this->name().'/'.$action.'@'.$this->module()->name();
    }
}