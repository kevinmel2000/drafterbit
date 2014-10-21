<?php

if ( ! function_exists('comment'))
{
    /**
     * admin url
     *
     * @param string $sub
     * @return string
     */
     function comment($id)
     {
          $comments = app()->getExtension('blog')->getComments($id);

          $data['comments'] = $comments;
          $data['post_id'] = $id;

          $content = _render($comments, $id);

          return $content;
     }

     function _render($comments, $post_id, $parent_id = 0)
     {
          $content = '';
          foreach ($comments as $comment) {
               $comment->childs = _render($comment->childs, $post_id, $comment->id);
               
               $data['comment'] = $comment;
               $data['parent_id'] = $parent_id;
               $data['post_id'] = $post_id;
               $content .= app('twig')->render('comment.html', $data);
          }

          return $content;
     }
}