<?php namespace Drafterbit\Core;

class WidgetUIBuilder {

	public function build(Widget $widget, $id = 0)
	{

		$ui= '';

		if($param = $widget->config('param'))
		foreach ($param as $input) {
			switch ($input['type']) {
				case 'text';
					$ui .= $this->text($input['name'], $input['default']);
					break;
				case 'textarea':
					$ui .= $this->textarea($input['name'], $input['default']);
					break;
				case 'select':
					$ui .= $this->select($input['name'], $input['default'], $input['options']);
					break;
				case 'password':
					$ui .= $this->password($input['name']);
					break;
				case 'hidden':
					$ui .= $this->hidden($input['name']);
					break;
				case 'radio':
					$ui .= $this->radio($input['name'], $input['default'], $input['options']);
					break;
				case 'checkbox':
					$ui .= $this->checkbox($input['name'], $input['default'], $input['options']);
					break;
				case 'file':
					$ui .= $this->file($input['name']);
				default:
					break;
			}
		}

		return '<form type="post">'.
					'<div><label>Title</label><input type="text" name="title"></div>'.
			$ui.
			'<a href="#" data-id="'.$id.'" class="widget-remover">Remove</a><input type="submit" name="submit" value="Save">';
	}

	protected function text($name, $default)
	{
		return "<label>$name</label><input type='text' name='$name' value='$default'>";
	}

	protected function textarea($name, $default)
	{
		return "<label>$name</label><br/><textarea name='$name'>$default</textarea>";
	}

	protected function select($name, $default, $options)
	{
		$html = "<select name='$name'>";

		foreach ($options as $option) {
			$html .= "<option value='{$option['value']}'>{$option['label']}</option>";
		}

		return $html .= "</select>";
	}
}
