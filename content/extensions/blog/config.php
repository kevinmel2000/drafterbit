<?php return [
    
    'name' => 'blog',
    'ns' => 'Drafterbit\\',
    'autoload' => [
        'psr-4' => [
            'Drafterbit\\Blog\\' => 'src'
        ]
    ],
    'nav' => [
        [ 'id' => 'blog', 'label' => 'Blog', 'href' => 'blog', 'parent' => 'content'],
        [ 'id' => 'comments', 'label' => 'Comments', 'href' => 'blog/comments', 'order' => 2],
        [ 'id' => 'blog-setting', 'label' => 'Blog', 'href' => 'blog/setting', 'parent' => 'setting']
    ],
    'permissions' => [
        'post.view' => 'view post',
        'post.edit' => 'edit post',
        'post.save' => 'save a post',
        'post.delete' => 'delete or trash post',
        'comment.view' => 'view comment',
        'comment.delete' => 'delete comment',
    ]
];