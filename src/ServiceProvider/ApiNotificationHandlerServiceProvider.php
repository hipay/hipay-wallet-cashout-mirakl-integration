<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Notification\Handler as NotificationHandler;

class ApiNotificationHandlerServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {

        $app['api.notification.handler'] = $app->share(
            function ($app) {
                $notificationHandler = new NotificationHandler(
                    $app['hipay.event.dispatcher'], $app['monolog'],
                    $app['operations.repository'], $app['vendors.repository'],
                    $app['log.vendors.repository'], $app['api.hipay.factory'],
                    $app['log.operations.repository']
                );

                return $notificationHandler;
            }
        );
    }

    public function boot(Application $app)
    {

    }
}