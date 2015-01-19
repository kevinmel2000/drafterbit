<?php namespace Drafterbit\Blog\Models;

use Drafterbit\Framework\Model;

class Dashboard extends Model
{

    public function recentComments()
    {
        $data['comments'] = $this->model('@blog\Comment')->take(5);
        return $this->render('@blog/dashboard/recent-comments', $data);
    }
}