<?php

namespace Fsalehpour\RabbitMQ\Providers;

use Fsalehpour\RabbitMQ\RabbitMQ;
use Illuminate\Support\ServiceProvider;

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
            return new RabbitMQ();
        });
    }
}
