<?php namespace Drafterbit\Blog\Models;

class Post extends \Drafterbit\Framework\Model
{
    public function all($filter = array())
    {
        $query = $this->get('db')->createQueryBuilder();

        $query
            ->select('p.*, u.email as authorEmail, u.real_name as authorName, u.username')
            ->from('#_posts', 'p')
            ->leftJoin('p', '#_users', 'u', 'p.user_id = u.id');

        if(isset($filter['status'])) {
            
            $status = $filter['status'];
            
            if ($status == 'trashed') {
                $query->where('p.deleted_at != :deleted_at');
                $query->setParameter(':deleted_at', '0000-00-00 00:00:00');
            } else {
                $query->Where('p.deleted_at = :deleted_at');
                $query->setParameter(':deleted_at', '0000-00-00 00:00:00');

                if ($status !== 'all') {
                    $query->andWhere('p.status = :status');
                    $s = $status == 'published' ? 1 : 0;
                    $query->setParameter(':status', $s);
                }
            }
        }
        
        return $query->getResult();
    }

    public function take($limit, $offset, $filter = array())
    {
        $query = $this->withQueryBuilder()
            ->select('p.*, u.email as authorEmail, u.real_name as authorName, u.username')
            ->from('#_posts', 'p')
            ->leftJoin('p', '#_users', 'u', 'p.user_id = u.id')
            ->Where('p.deleted_at = :deleted_at')
            ->setParameter(':deleted_at', '0000-00-00 00:00:00')
            ->andWhere('p.status = :status')
            ->setParameter(':status', 1)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if($filter) {
            foreach ($filter as $key => $value) {
                switch ($key) {
                    case 'username':
                        $query->andWhere('u.username = :username');
                        $query->setParameter(':username', $value);
                        break;
                    case 'tag':
                        $query->join('p', '#_posts_tags', 'pt', 'pt.post_id = p.id');
                        $query->join('pt', '#_tags', 't', 't.id = pt.tag_id');
                        $query->andWhere('t.slug = :tag');
                        $query->setParameter(':tag', $value);
                        break;            
                    
                    default:
                        # code...
                        break;
                }
            }
        }
    
        return $query->getResult();
    }

    public function insert($data)
    {
        $this->get('db')->insert('#_posts', $data);
        return $this->get('db')->lastInsertId();
    }

    public function update($data, $id)
    {
        return
        $this->get('db')->update('#_posts', $data, array('id' => $id));
    }
    /**
     * Delete post and related entities permanently
     *
     * @param  array $ids
     * @return void
     */
    public function delete($ids)
    {
        $ids = (array) $ids;
        $ids = array_map(
            function($v){
                return "'$v'";
            },
            $ids
        );
        $idString = implode(',', $ids);

        $this->withQueryBuilder()
            ->delete('#_comments')
            ->where('post_id IN ('.$idString.')')
            ->execute();

        $this->withQueryBuilder()
            ->delete('#_posts_tags')
            ->where('post_id IN ('.$idString.')')
            ->execute();
        
        $this->withQueryBuilder()
            ->delete('#_posts')
            ->where('id IN ('.$idString.')')
            ->execute();

    }

    public function getBy($field, $value)
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();
        
        return (object)
        $queryBuilder->select('*')->from('#_posts', 'p')
            ->where("$field = '$value'")
            ->execute()->fetch();
    }

    public function clearTag($id)
    {
        return
        $this->get('db')
            ->delete('#_posts_tags', array("post_id" => $id));
    }

    public function addTag($tagId, $id)
    {
        $data['post_id'] = $id;
        $data['tag_id'] = $tagId;
        return
        $this->get('db')->insert('#_posts_tags', $data);
    }

    public function getTags($id)
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();

        return
        $queryBuilder
            ->select('t.label, t.slug')
            ->from('#_tags', 't')
            ->innerJoin('t', '#_posts_tags', 'pt', 't.id = pt.tag_id')
            ->where("pt.post_id = '$id'")
            ->execute()->fetchAll();
    }

    public function getSingleBy($field, $value)
    {
        return
        $this->get('db')->createQueryBuilder()
            ->select('p.*, u.real_name as authorName, u.username')
            ->from('#_posts', 'p')
            ->innerJoin('p', '#_users', 'u', 'p.user_id = u.id')
            ->where("p.$field = :value")
            ->setParameter(':value', $value)
            ->execute()->fetch();
    }

    /**
     * Restore trashed pages
     *
     * @return void
     */
    public function restore($ids)
    {
        $ids = array_map(
            function($v){
                return "'$v'";
            },
            $ids
        );

        $idString = implode(',', $ids);
        $deleted_at = new \Carbon\Carbon;

        $this->withQueryBuilder()
            ->update('#_posts', 'p')
            ->set('deleted_at', "'0000-00-00 00:00:00'")
            ->where('p.id IN ('.$idString.')')
            ->execute();
    }

    /**
     * Trash pages by given ids
     *
     * @param  array $ids
     * @return void
     */
    public function trash($ids)
    {
        $ids = array_map(
            function($v){
                return "'$v'";
            },
            $ids
        );
        $idString = implode(',', $ids);
        $deleted_at = new \Carbon\Carbon;

        $this->withQueryBuilder()
            ->update('#_posts', 'p')
            ->set('deleted_at', "'$deleted_at'")
            ->where('p.id IN ('.$idString.')')
            ->execute();
    }

    /**
     * Get post subscribers via comment
     */
    public function getSubscribers($postId)
    {
        return
        $this->withQueryBuilder()
            ->select('email')
            ->from('#_comments', 'c')
            ->where('post_id=:post_id')
            ->andWhere('subscribe=1')
            ->setParameter('post_id', $postId)
            ->getResult();
    }
}
