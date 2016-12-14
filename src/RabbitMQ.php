<?php
namespace Fsalehpour\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQ
{
    private $connection;
    private $channel;

    /**
     * RabbitMQ constructor.
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'), env('RABBITMQ_PASS', 'guest')
        );

        $this->channel = $this->connection->channel();
    }

    public function channel()
    {
        return $this->channel;
    }

}