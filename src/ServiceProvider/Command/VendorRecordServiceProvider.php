<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Vendor\RecordCommand;

class VendorRecordServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.vendor.record'] = $app->share(
            function ($app) {

            return new RecordCommand($app['monolog'], $app['vendor.processor']);
        }
        );
    }

    public function boot(Application $app)
    {

    }
}