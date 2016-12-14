<?php

namespace Fsalehpour\RabbitMQ\Facades;

use Illuminate\Support\Facades\Facade;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPFacade extends Facade
{
    public static function basic_publish_with_response($channel, $exchange, $routing_key, $body, $headers = [])
    {
        $response = null;
        $corr_id = uniqid();

        list ($callback_queue, ,) = $channel->queue_declare('', false, false, true, false);

        $callback = function ($msg) use ($corr_id, &$response) {
            if ($msg->get('correlation_id') == $corr_id) {
                $response = $msg->body;
            }
        };

        $channel->basic_consume($callback_queue, '', false, false, false, false, $callback);

        $msg = new AMQPMessage($body, array_merge($headers, [
            'correlation_id' => $corr_id,
            'reply_to'       => $callback_queue
        ]));

        $channel->basic_publish($msg, $exchange, $routing_key);

        while (!$response) {
            $channel->wait(null, false, env('RABBITMQ_TIMEOUT', 10));
        }

        return $response;
    }

    protected static function getFacadeAccessor()
    {
        return 'AMQP';
    }

}