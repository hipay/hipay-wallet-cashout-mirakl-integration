<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Log\LogVendorsRecoverCommand;

class LogVendorsRecoverServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.log.vendors.recover'] = $app->share(
            function ($app) {

            return new LogVendorsRecoverCommand(
                $app['monolog'], $app['vendor.processor'], $app['batch.repository'], $app['vendors.repository'],
                $app['log.vendors.repository']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}