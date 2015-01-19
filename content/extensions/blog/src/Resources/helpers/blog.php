<?php

if (! function_exists('comment')) {
    /**
     * Print comments and form
     *
     * @param  in $id post id
     * @return string
     * @todo   find the right way to do this
     */
    function comment($id)
    {
        $comments = app()->getExtension('blog')->getComments($id);

        $data['comments'] = $comments;
        $data['post_id'] = $id;

        $content = _render($comments, $id);

        $js = app('asset')->add('js', '@blog/js/comment/front-snippet.js')->dump('js');
        $js = '<script>'.$js.'</script>';

        $form = app('twig')->render('blog/comment/form.html', array('parent_id' => 0, 'post_id' => $id));

        return $content.$form.$js;
    }

    function _render($comments, $post_id)
    {
        $content = '';
        foreach ($comments as $comment) {
            $comment['childs'] = _render($comment['childs'], $post_id, $comment['id']);
            
            $data['parent_id'] = $comment['id'];
            $data['post_id'] = $post_id;

            $comment['form'] = app('twig')->render('blog/comment/form.html', $data);

            $data['comment'] = $comment;
            $content .= app('twig')->render('blog/comment/index.html', $data);
        }

        return $content;
    }
}

if (! function_exists('blog_url')) {
    /**
     * Blog url, this will create proper url for posts
     *
     * @param  string $path
     * @return string
     */
    function blog_url($path)
    {
        return app()->getExtension('blog')->getUrl($path);
    }
}
