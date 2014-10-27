<?php namespace Drafterbit\System\Log;

use Monolog\Logger;
use Doctrine\DBAL\Connection;
use Monolog\Handler\AbstractProcessingHandler;

class DoctrineDBALHandler extends AbstractProcessingHandler
{
    private $initialized = false;
    private $connection;
    private $statement;

    public function __construct(Connection $connection, $level = Logger::DEBUG, $bubble = true)
    {
        $this->connection = $connection;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $this->statement->execute(array(
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['message'],
            'time' => $record['datetime']->format('U'),
            'user_id' => isset($record['context']['user_id']) ? $record['context']['user_id'] : 0
        ));
    }

    private function initialize()
    {
        $this->statement = $this->connection->prepare(
            'INSERT INTO #_logs (channel, level, message, time, user_id) VALUES (:channel, :level, :message, :time, :user_id)'
        );

        $this->initialized = true;
    }
}