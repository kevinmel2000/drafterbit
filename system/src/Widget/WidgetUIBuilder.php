<?php namespace Drafterbit\System\Widget;

class WidgetUIBuilder {

	/**
	 * Build user interface for a widget
	 *
	 * @param Drafterbi\Widget\Widget $widget
	 */
	public function build(Widget $widget)
	{
		$title 		= isset($widget->data['title']) ? $widget->data['title'] : null;
		$id 		= isset($widget->data['id']) ? $widget->data['id'] : null;
		$name 		= isset($widget->data['name']) ? $widget->data['name'] : null;
		$position 	= isset($widget->data['position']) ? $widget->data['position'] : null;
		$theme 		= isset($widget->data['theme']) ? $widget->data['theme'] : null;
		
		$html  = form_open(null, array('class' => 'widget-edit-form'));

		$html .= '<div class="form-group">'.$this->text('Title', 'title', $title).'</div>';
		$html .= $this->hidden('id', $id);
		$html .= $this->hidden('name', $name);
		$html .= $this->hidden('position', $position);
		$html .= $this->hidden('theme', $theme);

		$param = $widget->config('input') ? $widget->config('input') : array();
		
		foreach ($param as $name => $config) {
			
			$html .= '<div class="form-group">';

			$name = $name;
			$type = $config['type'];

			if (isset($widget->data[$name])) {
				$default = $widget->data[$name];

			} else {

				$default = isset($config['default']) ? $config['default'] : null;
			}

			$options = isset($config['options']) ? $config['options'] : array();

			if(!method_exists($this, $type)) {
				throw new \RuntimeException("Type $type is not supported by Widget UI Builder");
			}

			$html .= empty($options) ?
				$this->$type($config['label'], "data[$name]", $default) :
				$this->$type($config['label'], "data[$name]", $default, $options);

			$html .= '</div">';
		}
		
		$html .= '<div class="clearfix" style="margin-top:10px;">';
		//$ui .= '<a href="#" data-id="'.$id.'" class="widget-remover">Remove</a>';
		$html .= input_submit('save', 'Save', 'class="btn btn-primary";');
		$html .= '<a href="#" class="btn btn-default" data-dismiss="modal" style="margin-left:10px;">Cancel</a>';
		$html .= '</div>';
		$html .= form_close();
		return $html;
	}

	/**
	 * Create text input
	 *
	 * @param string $name
	 * @param string $default
	 */
	protected function text($label, $name, $default)
	{
		return label(ucfirst($label), $name).input_text($name, $default, 'class="form-control"');
	}

	protected function textarea($label, $name, $default)
	{
		return label(ucfirst($label), $name).input_textarea($name, $default, 'class="form-control"');
	}

	protected function select($label, $name, $options, $default)
	{
		return label(ucfirst($label), $name).input_select($name, $options, $default, 'class="form-control"');
	}

	protected function hidden($name, $default)
	{
		return input_hidden($name, $default);
	}
}
