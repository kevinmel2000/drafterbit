<?php namespace Drafterbit\Extensions\System\Models;

class System extends \Drafterbit\Framework\Model
{

    public function all()
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();

        $sets = $queryBuilder
            ->select('st.*')
            ->from('#_system', 'st')
            ->getResult();

        $array = array();
        foreach ($sets as $set) {
            $array[$set['name']] = $set['value'];
        }

        return $array;
    }

    /**
     * Get system setting by key
     */
    public function fetch($key)
    {
        $config = $this->all();

        return isset($config[$key]) ? $config[$key] : null;
    }

    public function exists($key)
    {
        $qb = $this->get('db')->createQueryBuilder();
        $qb->select('*');
        $qb->from('#_system', 'st');
        $qb->where('name=:key');
        $qb->setParameter('key', $key);
        return $qb->execute()->fetch();
    }

    public function updateSetting($data)
    {
        foreach ($data as $key => $value) {
            if ($this->exists($key)) {
                $qb = $this->get('db')->createQueryBuilder();
                $qb->update('#_system', 'st');
                $qb->set('value', ':value');
                $qb->where('name=:key');
                $qb->setParameter(':key', $key);
                $qb->setParameter(':value', $value);
                $qb->execute();
            } else {
                $this->get('db')->insert('#_system', array('name' => $key, 'value' => $value));
            }
        }
    }

    public function updateTheme($theme)
    {
        return $this->updateSetting(array('theme' => $theme));
    }
}
