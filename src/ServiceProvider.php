<?php

namespace Juanparati\Sendinblue;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;


class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Boot as swift transport
        $this->app['swift.transport']->extend('sendinblue.v3', function ($app) {
            return new Transport($app[Client::class]);
        });
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        // Register Sendinblue API client
        $this->app->singleton(Client::class, function ($app)
        {
            return new Client($app['config']['services.sendinblue.v3']);
        });

        $this->app->alias(Client::class, class_basename(Client::class));


        // Register Sendinblue Template
        $this->app->bind(Template::class, function ($app)
        {
            return (new Template($app[Client::class], new TemplateTransport($app[Client::class])));
        });

        $this->app->alias(Template::class, 'Sendinblue' . class_basename(Template::class));


        // Register Sendinblue SMS
        $this->app->bind(SMS::class, function ($app)
        {
            return (new SMS(new SMSTransport($app[Client::class])));
        });

        $this->app->alias(SMS::class, 'Sendinblue' . class_basename(SMS::class));


    }
}
