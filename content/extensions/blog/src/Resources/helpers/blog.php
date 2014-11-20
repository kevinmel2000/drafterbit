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

          $js = app('asset')->add('js', '@blog/js/comment/front-snippet.js')->dump('js');
          $js = '<script>'.$js.'</script>';

          // @todo, clean this
          $form = '<div>
          <form method="post" action="'.base_url('blog/comment/submit').'">
               <div>
                    <label class="label" for="comment[name]">Name</label>
                    <input type="text" name="comment[name]" required/>
               </div>
               <div>
                    <label class="label" for="comment[email]">Email</label>
                    <input type="email" name="comment[email]" required />
               </div>
               <div>
                    <label class="label" for="comment[url]">URL</label>
                    <input type="text" name="comment[url]"/>
               </div>
               <div>
                    <label class="label">Comment</label>
                    <textarea name="comment[content]"></textarea>
               </div>
               <div>
                    <input type="hidden" name="comment[parent_id]" value="0">
                    <input type="hidden" name="comment[post_id]" value="'.$id.'">
                    <input type="submit" name="submit" value="Submit"/>
               </div>
          </form>
     </div>';

          return $content.$form.$js;
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