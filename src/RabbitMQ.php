<?php
namespace Fsalehpour\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    private $connection;

    /**
     * RabbitMQ constructor.
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'), env('RABBITMQ_PASS', 'guest')
        );
    }

    public function channel()
    {
        return $this->connection->channel();
    }

    /**
     * @param $ch
     * @param $msg
     * @return mixed
     */
    public function confirmed_publish(AMQPMessage $msg, $exchange, $routing_key, AMQPChannel $channel = null)
    {
        $ch = is_null($channel) ? $this->channel() : $channel;
        $response = true;
        $ch->set_ack_handler(function (AMQPMessage $msg) use (&$response) {
            Log::info('ack received');
            $response = $response && true;
        });
        $ch->set_nack_handler(function (AMQPMessage $msg) use (&$response) {
            Log::info('nack received');
            $response = $response && false;
        });
        $ch->set_return_listener(function ($replyCode, $replyText, $exchange, $routingKey, AMQPMessage $msg) use (&$response) {
            Log::info('return received');
            $response = $response && false;
        });

        $ch->confirm_select();
        $ch->basic_publish($msg, $exchange, $routing_key, true);
        $ch->wait_for_pending_acks_returns(5);
        if (is_null($channel)) {
            $ch->close();
        }
        return $response;
    }
}