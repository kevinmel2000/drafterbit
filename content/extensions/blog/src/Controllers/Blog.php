<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\BackendController;
use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;

class Blog extends BackendController
{
    public function index()
    {
        $status = 'untrashed';

        $posts = $this->model('Post')->all(['status' => $status]);

        $data['id']        = 'posts';
        $data['title']     = __('Blog');
        $data['status']    = $status;
        $data['action']    = admin_url('blog/trash');
        $data['blogTable'] = $this->dataTable('posts', $this->_table(), $posts);

        return $this->render('@blog/admin/index', $data);
    }

    public function trash()
    {
        $post = $this->get('input')->post();
        $model = $this->model('Post');

        $postIds = $post['posts'];

        switch($post['action']) {
            case "trash":
                $model->trash($postIds);
                break;
            case 'delete':
                $model->delete($postIds);
            case 'restore':
                $model->restore($postIds);
                break;
            default:
                break;
        }
    }

    public function filter($status)
    {
        $posts = $this->model('@blog\Post')->all(['status' => $status]);
        
        $editUrl = admin_url('blog/edit');

        $pagesArr  = array();

        foreach ($posts as $post) {
            $data = array();
            $data[] = '<input type="checkbox" name="posts[]" value="'.$post['id'].'">';
            $data[] = $status !== 'trashed' ? "<a class='post-edit-link' href='$editUrl/{$post['id']}'>{$post['title']}</a>" : $post['title'];
            $data[] ='<a href="'.admin_url('blog/edit/'.$post['id']).'">'.$post['authorName'].'</a>';

            if ($status == 'trashed') {
                $s = ucfirst($status);
            } else {
                $s = $post['status'] == 1 ? 'Published' : 'Unpublished';
            }

            $data[] = $s;
            $data[] = $post['created_at'];

            $pagesArr[] = $data;
        }

        $ob = new \StdClass;
        $ob->data = $pagesArr;
        $ob->recordsTotal= count($pagesArr);
        $ob->recordsFiltered = count($pagesArr);

        return $this->jsonResponse($ob);
    }

    private function _table()
    {
        $editUrl = admin_url('blog/edit');
        $userUrl = admin_url('user/edit');

        return array(
            [
                'field' => 'title',
                'label' => 'Title',
                'width' => '40%',
                'format' => function($value, $item) use ($editUrl) {
                    return "<a href='$editUrl/{$item['id']}'>$value</a>";
                }],
            [
                'field' => 'authorName',
                'label' => 'Author',
                'width' => '20%',
                'format' => function($value, $item) use ($userUrl) {
                    return "<a href='$userUrl/{$item['user_id']}'>$value</a>";
                }],
            [
                'field' => 'status',
                'label' => 'Status',
                'width' => '20%',
                'format' => function($value, $item) use ($userUrl) {
                    return $value == 1 ? 'Published' : 'Unpublished';
                }],
            [
                'field' => 'created_at',
                'label' => 'Created',
                'width' => '20%',
                'format' => function($value, $item){
                    return $value;
                }],
        );
    }

    public function edit($id)
    {
        $tagOptionsArray = $this->model('Tag')->all();
        $tagOptions = '[';
        foreach ($tagOptionsArray as $tO) {
            $tO = (object) $tO;
            $tagOptions .= "'{$tO->label}',";
        }
        $tagOptions = rtrim($tagOptions, ',').']';

        if ('new' == $id) {
            $data = array(
                'postId' => null,
                'postTitle' => null,
                'slug' => null,
                'content' => null,
                'tagOptions' => $tagOptions,
                'tags' => array(),
                'status' => 1,
                'title' => __('New Post'),
            );
        } else {
            $model = $this->model('Post'); 
            $post = $model->getBy('id', $id);
            $post->tags = $model->getTags($id);
            
            $tags = array();
            foreach ($post->tags as $tag) {
                $tag = (object) $tag;
                $tags [] = $tag->label;
            }

            $data = array(
                'postId' => $id,
                'postTitle' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'tags' => $tags,
                'tagOptions' => $tagOptions,
                'status' => $post->status,
                'title' => __('Edit Post'),
            );
        }

        $data['id'] = 'post-edit';
        $data['action'] = admin_url('blog/save');
        
        return $this->render('@blog/admin/edit', $data);
    }

    public function save()
    {
        $model = $this->model('Post');
        
        try {
            $postData = $this->get('input')->post();

            $this->validate('blog', $postData);

            $id = $postData['id'];

            if ($id) {
                $data = $this->createUpdateData($postData);
                $model->update($data, $id);
            
            } else {
                $data = $this->createInsertData($postData);
                $id = $model->insert($data);
            }

            if (isset($postData['tags'])) {
                $this->insertTags($postData['tags'], $id);
            }

            // @todo log here
            return $this->jsonResponse(['message' => __('Post succesfully saved'), 'status' => 'success', 'id' => $id]);

        } catch (ValidationFailsException $e) {
            return $this->jsonResponse(
                ['error' => [
                    'type' => 'validation',
                    'message' => $e->getMessage(),
                    'messages' => $e->getMessages()
                ]
                ]
            );
        }
    }

    /**
     * Parse post data to insert to db
     *
     * @param  array $post
     * @return array
     */
    protected function createInsertData($post, $isUpdate = false)
    {
        $data = array();
        
        $data['slug']       = $post['slug'];
        $data['title']      = $post['title'];
        $data['status']     = $post['status'];
        $data['content']    = $post['content'];
        $data['user_id']    = $this->get('session')->get('user.id');
        $data['updated_at'] = $this->get('time')->now();
        
        if (! $isUpdate) {
            $data['created_at'] = $this->get('time')->now();
        }

        return $data;
    }

    /**
     * Parse post data for update
     *
     * @param  array $post
     * @return array
     */
    public function createUpdateData($post)
    {
        return $this->createInsertData($post, true);
    }

    protected function insertTags($tags, $postId)
    {
        $post = $this->model('Post');
        $tag = $this->model('Tag');
        
        //delete all related tag first
        $post->clearTag($postId);

        foreach ($tags as $t) {
            if (! $tagId = $tag->getIdBy('label', $t)) {
                $tagId = $tag->save($t);
            }

            $post->addTag($tagId, $postId);
        }
    }

    public function setting()
    {
        $data['title'] = __('Blog Setting');

        if ($post = $this->get('input')->post()) {
            $this->model('@system\System')->updateSetting(
                [
                'comment.moderation' => $post['comment_moderation'],
                'post.per_page' => $post['post_perpage']
                ]
            );
        }

        $data['mode'] = $this->model('@system\System')->fetch('comment.moderation');
        $data['postPerpage'] = $this->model('@system\System')->fetch('post.per_page');
        return $this->render('@blog/admin/setting', $data);
    }
}
