<?php namespace Drafterbit\Extensions\System\Models;

class Log extends \Drafterbit\Framework\Model
{

    public function all()
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();
        $stmt = $queryBuilder
            ->select('l.*')
            ->from('#_logs', 'l')
            ->orderBy('time', 'DESC');
        return $stmt->execute()->fetchAll();
    }

    public function recent()
    {
        $queryBuilder = $this->get('db')->createQueryBuilder();
        $stmt = $queryBuilder
            ->select('l.*')
            ->from('#_logs', 'l')
            ->orderBy('time', 'DESC')->setMaxResults(10);

        return $stmt->execute()->fetchAll();
    }

    public function delete($id)
    {
        return $this->get('db')->delete('#_logs', ['id' => $id]);
    }

    public function clear()
    {
        return $this->get('db')->exec('TRUNCATE TABLE #_logs');
    }
}
