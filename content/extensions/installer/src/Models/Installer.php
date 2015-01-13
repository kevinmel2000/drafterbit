<?php namespace Drafterbit\Extensions\Installer\Models;

use Drafterbit\Framework\Model;

class Installer extends Model {
    
    public function createAdmin($email, $password)
    {
        $permissions = array();

        foreach (array_values($this->get('app')->getPermissions()) as $extPermissions) {
            $permissions = array_merge($permissions, array_keys($extPermissions));
        }

        $this->get('db')->insert('#_roles', [
            'label'=> 'Administrator',
            'description' => 'God of the site',
            'permissions' => json_encode($permissions)
        ]);

        $roleId = $this->get('db')->lastInsertId();

        $user['email'] = $email;
        $user['password'] = password_hash($password, PASSWORD_BCRYPT);
        $user['real_name'] = 'Administrator';
        $user['status'] = 1;

        $this->get('db')->insert('#_users', $user);
        $userId = $this->get('db')->lastInsertId();
        
        $this->get('db')->insert('#_users_roles', [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);

        return array('userId' => $userId, 'roleId' => $roleId);
    }

    public function systemInit($name, $desc, $email, $userId)
    {
        $page = $this->createDummyPage($userId);

        $data['site.name'] = $name;
        $data['site.description'] = $desc;
        $data['email'] = $email;
        $data['language'] = 'en_EN';
        $data['format.date'] = 'm dS Y';
        $data['format.time'] = 'H:m:s';
        $data['theme'] = 'default';
        $data['homepage'] = $page;

        $extensions = array(
            "pages" => '0.1.0',
            "blog" => '0.1.0',
            "user" => '0.1.0',
            "files" => '0.1.0');
        
        $data['extensions'] = json_encode($extensions);
        $data['timezone'] = "Asia/Jakarta";

        $q = "INSERT INTO #_system (name, value) ";
        $q .= "VALUES ";

        $param = [];
        foreach ($data as $key => $value) {
            $q .= "(?, ?),";
            $param[] = $key;
            $param[] = $value;
        }

        $q = rtrim($q, ',').';';
        return $this->get('db')->executeUpdate($q,$param);
    }

    public function createDummyPage($user)
    {
        $data['title'] = "Hello World";
        $data['slug'] = "hello-world";
        $data['content'] = "This is Hello World Page is to be edited or removed.";
        $data['user_id'] = $user;
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['status'] = 1;

        $this->get('db')->insert('#_pages', $data);
        $id = $this->get('db')->lastInsertId();
        return "pages:$id";
    }
}