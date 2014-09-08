<?php namespace Drafterbit\CMS\Widget;

class WidgetUIBuilder {

	/**
	 * Build user interface for a widget
	 *
	 * @param Drafterbi\CMS\Widget\Widget $widget
	 */
	public function build(Widget $widget, $id = 0, $data = array())
	{
		$ui  = form_open();
		$ui .= $this->text('title', null);

		$param = $widget->config('param') ? $widget->config('param') : array();
		
		foreach ($param as $input) {
			
			$name = $input['name'];
			$type = $input['type'];
			$default = isset($input['default']) ? $input['default'] : null;
			$options = isset($input['options']) ? $input['options'] : array();

			if(!method_exists($this, $type)) {
				throw new \RuntimeException("Type $type is not supported by Widge UI Builder");
			}

			$ui .= empty($options) ?
				$this->$type($name, $default) :
				$this->$type($name, $default, $options);
		}
		
		$ui .= '<div class="clearfix" style="margin-top:10px;">';
		//$ui .= '<a href="#" data-id="'.$id.'" class="widget-remover">Remove</a>';
		$ui .= input_submit('save', 'Save', 'class="btn btn-primary";');
		$ui .= ' or <a href="#" class="btn" data-dismiss="modal">Cancel</a>';
		$ui .= '</div>';
		$ui .= form_close();
		return $ui;
	}


	/**
	 * Create text input
	 *
	 * @param string $name
	 * @param string $default
	 */
	protected function text($name, $default)
	{
		return label(ucfirst($name), $name).input_text($name, $default, 'class="form-control"');
	}

	protected function textarea($name, $default)
	{
		return label(ucfirst($name), $name).input_textarea($name, $default, 'class="form-control"');
	}

	protected function select($name, $options, $default)
	{
		return label(ucfirst($name), $name).input_select($name, $options, $default, 'class="form-control"');
	}

	protected function checkbox($name, $default)
	{
		return input_checkbox($name, $default).label(ucfirst($name), $name, 'class="form-control"');
	}

	protected function radio($name, $default)
	{
		return input_radio($name, $default).label(ucfirst($name), $name, 'class="form-control"');
	}

	private function wrap($ui)
	{
		return '<div class="form-group">'.$ui.'</div>';
	}
}
