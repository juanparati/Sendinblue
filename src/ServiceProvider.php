<?php

namespace Juanparati\Sendinblue;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

/**
 * Class ServiceProvider.
 *
 * @package Juanparati\Sendinblue
 */
class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Mail::extend('sendinblue.v3', function () {
            return (new SendinblueTransportFactory)->create(
                new Dsn(
                    'sendinblue+api',
                    'default',
                    $this->app['config']['services.sendinblue.v3.key'],
                )
            );
        });
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
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
