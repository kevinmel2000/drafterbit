<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\BackendController;
use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;

class Comment extends BackendController
{
    public function index()
    {
        $model = $this->model('Comment');

        $comments = $model->all(['status' => 'active']);

        $data['id']     = 'comments';
        $data['title']  = __('Comments');
        $data['status'] = 1;
        $data['action'] = admin_url('comments/trash');
        $data['commentsTable'] = $this->dataTable('comments', $this->_table(), $comments);

        return $this->render('@blog/admin/comments/index', $data);
    }

    public function trash()
    {
        $post = $this->get('input')->post();

        $commentIds = $post['comments'];

        switch($post['action']) {
            case "trash":
                foreach ($commentIds as $id) {
                    $this->model('@blog\Comment')->trash($id);
                }
                break;
            case 'delete':
                $this->model('@blog\Comment')->delete($commentIds);
            case 'restore':
                $this->model('@blog\Comment')->restore($commentIds);
                break;
            default:
                break;
        }
    }

    public function filter($status)
    {
        $comments = $this->model('@blog\Comment')->all(['status' => $status]);
        
        $arr  = array();

        foreach ($comments as $comment) {
            $data = array();
            $data[] = "<input type=\"checkbox\" name=\"comments[]\" value=\"{$comment['id']}\">";
            $data[] = '<img alt="" src="'.gravatar_url($comment['email'], 40).'"/>'.$comment['name'].'<br/><a href="mailto:'.$comment['email'].'">'.$comment['email'].'</a>';

            $data[] = $this->contentFormat($comment['content'], $comment);;

            $data[] = '<a href="'.admin_url('blog/edit/'.$comment['post_id']).'">'.$comment['title'].'</a><br/>'.$comment['created_at'];
            
            $arr[] = $data;
        }

        $ob = new \StdClass;
        $ob->data = $arr;
        $ob->recordsTotal= count($arr);
        $ob->recordsFiltered = count($arr);

        return $this->jsonResponse($ob);
    }

    private function _table()
    {
        return array(
            [
                'field' => 'name',
                'label' => 'Author',
                'width' => '25%',
                'format' => function($value, $item){
                    return "<img src='".gravatar_url($item['email'])."'/>{$value} <div><a href=\"mailto:{$item['email']}\">{$item['email']}</a></div>";
                }],
            [
                'field' => 'content',
                'label' => 'Comment',
                'width' => '55%',
                'format' => array($this, 'contentFormat')
            ],
            [
                'field' => 'post_id',
                'label' => 'In Respose to',
                'width' => '20%',
                'format' => function($value, $item){
                    return '<a href="'.admin_url('blog/edit/'.$value).'">'.$item['title'].'</a><br/>'.$item['created_at'];
                }
            ]
        );
    }

    private function contentFormat($content, $item)
    {
        $data['content'] = $content;
        $data['itemId'] = $item['id'];
        $data['postId'] = $item['post_id'];
        $data['status'] = $item['status'];
        $data['deletedAt'] = $item['deleted_at'];

        if ($data['status'] != 2) {
            $data['display'] = $data['status'] == 1 ? 'inline' : 'none';
            $data['display2'] = $data['status'] == 0 ? 'inline' : 'none';
        }

        return $this->render('@blog/admin/comments/item.php', $data);
    }

    public function submit()
    {
        try {
            $comment = $this->get('input')->post();

            $this->validate('comment', $comment);

            $moderation = $this->model('@system\System')->fetch('comment.moderation');

            $data['name']       = $comment['name'];
            $data['email']      = $comment['email'];
            $data['url']        = $comment['url'];
            $data['content']    = $comment['content'];
            $data['parent_id']  = $comment['parent_id'];
            $data['post_id']    = $postId = $comment['post_id'];
            
            if ($moderation == 0) {
                $data['status'] = 1;
            } elseif ($moderation == 1) {
                $data['status'] = 0;
            }
            
            $data['created_at'] = $this->get('time')->now();
            $data['subscribe']  = isset($comment['subscribe']) ? $comment['subscribe'] : 0;

            $id = $this->model('@blog\Comment')->insert($data);
            $referer = $this->get('input')->headers('referer');

            $mailConfig = $this->model('@system\System')->fetch('smtp.host');

            if($mailConfig) {            
                //send notification to admin
                $toEmail = $this->model('@system\System')->fetch('email');
                $subscriber = $this->getSubscribers($postId);

                array_unshift($subscriber, $toEmail);

                // @todo improve mail message
                $messageBody = $this->render('@blog/mail/new-comment-notif', $data);

                $message = $this->get('mail')
                    ->setFrom($toEmail)
                    ->setTo($subscriber)
                    ->setSubject('New Comment Notification')
                    ->setBody($messageBody);

                $this->get('mailer')->send($message, $failures);
            }

            return redirect($referer.'#comment-'.$id);
        
        } catch (ValidationFailsException $e) {
            $messages = $e->getMessages();
            
            return implode('<br/>', array_values($messages));
        }
    }

    public function status()
    {
        $id = $this->get('input')->post('id');
        $status = $this->get('input')->post('status');

        $this->model('Comment')->changeStatus($id, $status);
    }

    public function quickReply()
    {
        $data['post_id']   = $this->get('input')->post('postId');
        $data['content']   = $this->get('input')->post('comment');
        $data['parent_id'] = $this->get('input')->post('parentId');

        $session = $this->get('session');
        $data['user_id'] = $session->get('user.id');
        $data['name']    = $session->get('user.name');
        $data['email']   = $session->get('user.email');
        $data['created_at'] = $this->get('time')->now();

        $id = $this->model('Comment')->insert($data);

        return $this->jsonResponse(['msg' => 'Comment saved', 'status' => 'success']);
    }

    public function quickTrash()
    {
        $id = $this->get('input')->post('id');
        $this->model('Comment')->trash($id);
        return $this->jsonResponse(['msg' => 'Comment moved to trash', 'status' => 'warning']);
    }

    /**
     * Get comment subscriber
     *
     * @param int $postId
     */
    private function getSubscribers($postId)
    {
        $comments =  $this->model('Post')->getSubscribers($postId);

        $subscriber = array();
        foreach ($comments as $comment) {
            $subscriber[] = $comment['email'];
        }

        return array_unique($subscriber);
    }
}
