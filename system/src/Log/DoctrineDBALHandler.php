<?php namespace Drafterbit\System\Log;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Doctrine\DBAL\Connection;

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
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS #_logs'
            .'(id INT(11) PRIMARY KEY AUTO_INCREMENT, channel VARCHAR(255), level INT(11), message TEXT, time INTEGER UNSIGNED)'
        );

        $this->statement = $this->connection->prepare(
            'INSERT INTO #_logs (channel, level, message, time, user_id) VALUES (:channel, :level, :message, :time, :user_id)'
        );

        $this->initialized = true;
    }
}