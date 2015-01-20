<?php namespace Drafterbit\Extensions\Installer\Models;

use Drafterbit\Framework\Model;

class Install extends Model
{   
    public function createAdmin($email, $password)
    {
        $permissions = array();

        foreach (array_values($this->get('app')->getPermissions()) as $extPermissions) {
            $permissions = array_merge($permissions, array_keys($extPermissions));
        }

        $this->get('db')->insert(
            '#_roles',
            [
            'label'       => 'Administrator',
            'description' => 'God of the site',
            'permissions' => json_encode($permissions)
            ]
        );

        $roleId = $this->get('db')->lastInsertId();

        $user['email']     = $email;
        $user['password']  = password_hash($password, PASSWORD_BCRYPT);
        $user['username']  = 'admin';
        $user['real_name'] = 'Administrator';
        $user['status']    = 1;

        $this->get('db')->insert('#_users', $user);
        $userId = $this->get('db')->lastInsertId();
        
        $this->get('db')->insert(
            '#_users_roles',
            [
            'user_id' => $userId,
            'role_id' => $roleId
            ]
        );

        return array('userId' => $userId, 'roleId' => $roleId);
    }

    public function systemInit($name, $desc, $email, $userId)
    {
        $page = $this->createSamplePage($userId);
        $this->createMenu($page);
        $firstPost = $this->createFirstPost($userId);
        $this->createFirstComment($firstPost);
        $this->addWidget();

        $data['site.name']        = $name;
        $data['site.description'] = $desc;
        $data['email']            = $email;
        $data['language']         = 'en_EN';
        $data['format.date']      = 'm dS Y';
        $data['format.time']      = 'H:m:s';
        $data['theme']            = 'default';
        $data['homepage']         = 'blog';

        $extensions = array(
            "pages" => '0.1.0',
            "blog"  => '0.1.0',
            "user"  => '0.1.0',
            "files" => '0.1.0');
        
        $data['extensions'] = json_encode($extensions);
        $data['timezone']   = "Asia/Jakarta";
        $data['dashboard']  = '[{"id":"recent-comments","display":1,"position":1},{"id":"recent","display":1,"position":1},{"id":"stat","display":1,"position":2}]';
        $data['comment_moderation'] = 1;

        $q = "INSERT INTO #_system (name, value) ";
        $q .= "VALUES ";

        $param = [];
        foreach ($data as $key => $value) {
            $q .= "(?, ?),";
            $param[] = $key;
            $param[] = $value;
        }

        $q = rtrim($q, ',').';';
        return $this->get('db')->executeUpdate($q, $param);
    }

    private function createSamplePage($user)
    {
        $data['title']   = "Sample Page";
        $data['slug']    = "sample-page";
        $data['content'] = "This is Sample Page is to be edited or removed.";
        $data['user_id'] = $user;
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['status'] = 1;

        $this->get('db')->insert('#_pages', $data);
        $id = $this->get('db')->lastInsertId();
        return "pages:$id";
    }

    private function createMenu($page)
    {
        $data = [
            ['label' => "Home",
             'link' => '%base_url%',
             'sequence' => 1,
             'type' => 1,
             'position' => 'main',
             'theme' => 'default'],
            ['label' => "Sample Page",
             'page' => $page,
             'sequence' => 2,
             'type' => 2,
             'position'=>'main',
             'theme' => 'default']
        ];

        foreach ($data as $d) {
            $this->get('db')->insert('#_menus', $d);
        }
    }

    private function addWidget()
    {
        $data = [
            'name' => 'search',
            'title' => 'Search',
            'sequence' => 1,
            'position' => 'Sidebar',
            'theme' => 'default'
        ];
        
        $this->get('db')->insert('#_widgets', $data);
    }

    private function createFirstPost($user)
    {
        $data['title'] = "Hello World";
        $data['slug'] = "sample-page";
        $data['content'] = "This is Hello World Page is to be edited or removed.";
        $data['user_id'] = $user;
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['status'] = 1;

        $this->get('db')->insert('#_posts', $data);
        $id = $this->get('db')->lastInsertId();

        return $id;
    }

    private function createFirstComment($postId)
    {
        $data['content'] = 'This is test comment. Go to admin panel to delete this'.
        $data['name'] = 'Drafterbit';
        $data['email'] = 'noreply@drafterbit.org';
        $data['url'] = 'drafterbit.org';
        $data['subscribe'] = 0;
        $data['post_id'] = $postId;
        $data['status'] = 1;
        $data['created_at'] = $this->get('time')->now();

        $this->get('db')->insert('#_comments', $data);
    }
}