<?php namespace Drafterbit\Extensions\System\Monolog;

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
        ));
    }

    private function initialize()
    {
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS logs'
            .'(id INT(11) PRIMARY KEY AUTO_INCREMENT, channel VARCHAR(255), level INT(11), message TEXT, time INTEGER UNSIGNED)'
        );

        $this->statement = $this->connection->prepare(
            'INSERT INTO logs (channel, level, message, time) VALUES (:channel, :level, :message, :time)'
        );

        $this->initialized = true;
    }
}