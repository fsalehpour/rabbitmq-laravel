<?php

namespace Fsalehpour\RabbitMQ\Providers;

use Illuminate\Support\ServiceProvider;
use RabbitMQWrapper\RabbitMQ;

class RabbitMQProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AMQP', function($app) {
            return new RabbitMQ(env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'), env('RABBITMQ_PASS', 'guest'),
                env('RABBITMQ_VHOST', '/'));
        });
    }
}
