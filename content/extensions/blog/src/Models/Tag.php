<?php namespace Drafterbit\Blog\Models;

class Tag extends \Drafterbit\Framework\Model
{
    public function all()
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();
        return
        $queryBuilder
            ->select('*')
            ->from('#_tags', 't')
            ->execute()->fetchAll();
    }

    public function getBy($key, $value = null, $singleRequested = false)
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();
        $stmt = $queryBuilder->select('*')->from('#_tags', 't');

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $holder = ":$k";
                $queryBuilder->where("$k = $holder")
                    ->setParameter($holder, $v);
            }

        } else {
            $queryBuilder->where("$key = :$key")
                ->setParameter(":$key", $value);
        }

        $tags = $stmt->getResult();

        if ($singleRequested) {
            return reset($tags);
        }

        return $tags;
    }

    public function getSingleBy($key, $value = null)
    {
        return $this->getBy($key, $value, true);
    }

    public function getByPost($id)
    {
        return
        $this->withQueryBuilder()
            ->select('t.label, t.slug')
            ->from('#_tags', 't')
            ->innerJoin('t', '#_posts_tags', 'pt', 't.id = pt.tag_id')
            ->where("pt.post_id = :post_id")
            ->setParameter(':post_id', $id)
            ->getResult();
    }

    public function getIdBy($field, $value)
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();

        $tag = $queryBuilder
            ->select('*')
            ->from('#_tags', 't')
            ->where("$field = '$value'")
            ->execute()->fetch();

        return isset($tag['id']) ? $tag['id'] : false;
    }

    public function save($tag)
    {
        $data['label'] = $tag;
        $data['slug'] = slug($tag);

        $this->get('db')->insert('#_tags', $data);

        return $this->get('db')->lastInsertId();
    }

    public function getPosts($id)
    {
        return
        $this->withQueryBuilder()
            ->select('p.*, u.real_name as authorName, u.username')
            ->from('#_posts', 'p')
            ->leftJoin('p', '#_posts_tags', 'pt', 'pt.post_id = p.id')
            ->leftJoin('p', '#_users', 'u', 'p.user_id = u.id')
            ->where('pt.tag_id = :tag')
            ->setParameter('tag', $id)
            ->andWhere('p.status = :status')
            ->setParameter(':status', 1)
            ->getResult();
    }
}
