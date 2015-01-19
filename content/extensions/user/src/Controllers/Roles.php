<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;
use Drafterbit\Extensions\System\BackendController;

class Roles extends BackendController
{

    public function index()
    {
        // @todo disable editing for administrator
        $roles = $this->model('@user\Role')->all();
        
        $data['roles'] = $roles;

        $editUrl = admin_url('user/roles/edit');
        $tableHead = array(
            ['field' => 'label', 'label' => 'Role', 'format' => function($value, $item) use ($editUrl)  {
                return "<a href='$editUrl/{$item['id']}'>$value</a>";
            }],
            ['field' => 'description', 'label' => 'Description']
        );

        $data['id'] = 'roles';
        $data['title'] = __('Roles');
        $data['action'] = admin_url('user/roles/index-action');
        $data['rolesTable'] = $this->dataTable('roles', $tableHead, $roles);

        return $this->render('@user/admin/roles/index', $data);
    }

    public function indexAction()
    {

        $roles = $this->get('input')->post('roles');

        if (!$roles) {
            return $this->jsonResponse(
                [
                'message' => 'Please make selection',
                'status' => 'error'
                ]
            );
        }

        $action = $this->get('input')->post('action');

        switch($action) {
            case "delete":

                $freezed = array();
                $notfreezed = array();
                
                foreach ($roles as $role) {
                    if ($this->model('@user\Role')->getRoledUsers($role)) {
                        $freezed[] = $this->model('@user\Role')->getRoleName($role);
                    } else {
                        $notfreezed[] = $role;
                    }
                }

                if ($notfreezed) {
                    $this->model('@user\Role')->delete($notfreezed);
                }

                if (count($freezed) > 0) {
                    $message = 'Can not delete following roles: '.implode(', ', $freezed).'. Due to there are users roled by them';
                    $status = 'warning';
                } else {
                    $message = 'Selected roles was deleted';
                    $status = 'success';
                }

                break;
            default:
                break;
        }
        
        return $this->jsonResponse(['message' => $message, 'status' => $status]);
    }

    public function filter()
    {
        $roles = $this->model('@user\Role')->all();
        
        $editUrl = admin_url('user/roles/edit');

        $usersArr  = array();

        foreach ($roles as $role) {
            $data = array();
            $data[] = '<input type="checkbox" name="roles[]" value="'.$role['id'].'">';
            $data[] = "<a class='role-edit-link' href='$editUrl/{$role['id']}'> {$role['label']}</a>";
            $data[] = $role['description'];

            $usersArr[] = $data;
        }

        $ob = new \StdClass;
        $ob->data = $usersArr;
        $ob->recordsTotal= count($usersArr);
        $ob->recordsFiltered = count($usersArr);

        return $this->jsonResponse($ob);
    }

    public function edit($id = null)
    {
        $data['id'] = 'roles-edit';
        $data['permissions'] = $this->get('app')->getPermissions();

        $data['action'] = admin_url('user/roles/save');
        ;
        $data['roleId'] = $id;

        if ($role = $this->model('@user\Role')->getsingleBy('id', $id)) {
            $data['roleName'] = $role['label'];
            $data['description'] = $role['description'];
            $data['permissionIds'] = $role['permissions'] ? json_decode($role['permissions'], true) : array();
            $data['title'] = __('Edit Role');
        } else {
            $data['roleName'] = null;
            $data['description'] = null;
            $data['permissionIds'] = array();
            $data['title'] = __('New Role');
        }

        return $this->render('@user/admin/roles/edit', $data);
    }

    public function save()
    {
        $response = array();

            
        try {
            $posts = $this->get('input')->post();

            $this->validate('roles', $posts);

            //insert froup
            $data = $this->createRoleInsertData($posts);
            
            $id = $this->model('@user\Role')->save($posts['id'], $data);

            $response = array(
                'message' => __('Role saved !'),
                'status' => 'success',
                'id' => $id
            );

        } catch (ValidationFailsException $e) {
            $response['error'] = array(
                'type' => 'validation',
                'messages' => $e->getMessages()
            );
        }

        return $this->jsonResponse($response);
    }

    protected function createRoleInsertData($post)
    {
        $data = array();
        $data['label'] = $post['name'];
        $data['description'] = $post['description'];

        if (isset($post['permissions'])) {
            $data['permissions'] = json_encode($post['permissions']);
        }

        return $data;
    }
}
