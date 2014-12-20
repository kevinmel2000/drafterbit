<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;
use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;

class Menus extends BackendController {

	function index()
	{
		$currentTheme = $this->get('themes')->get();

		$positions = $currentTheme['menus'];

		$model = $this->model('@system\\Menus');

		$theme = $this->get('themes')->current();

		foreach ($positions as $position) {		
			$menus[$position] = $model->getByThemePosition($theme, $position);
		}

		$data['positions'] = $positions;
		$data['menus'] = $menus;
		$data['frontPageOptions'] = $this->get('app')->getFrontPageOption();
		
		$data['title'] = __('Menus');
		return $this->render('@system/setting/themes/menus', $data);
	}

	function save()
	{
		$post = $this->get('input')->post();

		try {
			$this->validate('menus', $post);

			$model = $this->model('@system\\Menus');
			$data = $this->createInsertData($post);
			$id = $model->save($post['id'], $data);
			$response = [
				'message' => 'Menu saved',
				'id' => $id,
			];
		} catch(ValidationFailsException $e) {
			$response = [
				'error' => [
					'type' => 'validation',
					'messages' => $e->getMessages(),
				]
			];
		}

		return $this->jsonResponse($response);
	}

	private function createInsertData($post)
	{
		$data['label'] = $post['label'];
		$data['type'] = $post['type'];
		$data['link'] = $post['link'];
		$data['page'] = $post['page'];

		return $data;
	}
}