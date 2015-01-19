<?php namespace Drafterbit\Blog;

use Drafterbit\Framework\ExtensionEvent;
use Drafterbit\Framework\Application;

class BlogExtension extends \Drafterbit\Framework\Extension
{

    public function boot()
    {
        $this['helper']->register('blog', $this->getResourcesPath('helpers/blog.php'));
        $this['helper']->load('blog');

        $ns = $this->getNamespace();
        $extensionClass = $ns.'\\Extensions\\TwigExtension';

        // this must be after path.theme registered
        if (class_exists($extensionClass)) {
            $this['twig']->addExtension(new $extensionClass);
        }

        $this->getApplication()->addFrontPageOption(
            ['blog' => [
            'label' => 'Blog',
            'controller' => '@blog\Frontend::index',
            'defaults' => array('slug' => 'blog')
            ]
            ]
        );

        $system = $this->model('@system\System')->all();

        if ('blog' === $system['homepage']) {
            $urlPattern = '{yyyy}/{mm}/{slug}';
            $pageUrlPattern = 'page/{page}';
            $tagUrlPattern = 'tag/{slug}';
        } else {
            $urlPattern = 'blog/{yyyy}/{mm}/{slug}';
            $pageUrlPattern = 'blog/page/{page}';
            $tagUrlPattern = 'blog/tag/{slug}';
        }
        
        $this['router']->addReplaces('%blog_url_pattern%', $urlPattern);
        $this['router']->addReplaces('%blog_page_url_pattern%', $pageUrlPattern);
        $this['router']->addReplaces('%blog_tag_url_pattern%', $tagUrlPattern);


        //log entities
        $this->getApplication()->addLogEntityFormatter(
            'post',
            function($id){
            
                $label = $this->model('@blog\Post')->getSingleBy('id', $id)['title'];
                return '<a href="'.admin_url('blog/edit/'.$id).'">'.$label.'</a>';
            }
        );
    }

    public function getComments($id)
    {
        $model = $this->model('@blog\Comment');

        $comments = $model->getByPostId($id);

        return $comments;
    }

    function getSearchQuery()
    {
        $query = $this['db']->createQueryBuilder()
            ->select('*')
            ->from('#_posts', 'p')
            ->where("p.title like :q")
            ->orWhere("p.content like :q");

        return array('blog', $query);
    }

    function getReservedBaseUrl()
    {
        return ['blog'];
    }

    public function getUrl($path)
    {
        $system = $this->model('@system\System')->all();

        if ('blog' !== $system['homepage']) {
            $path = "blog/".$path;
        }

        return base_url($path);
    }

    function dashboardWidgets()
    {
        return array(
            'recent-comments' => $this->model('@blog\Dashboard')->recentComments()
        );
    }

    function getStat()
    {
        $posts = $this->model('@blog\Post')->all(['status' => 'untrashed']);

        return array(
            'Post(s)' => count($posts)
        );
    }
}
