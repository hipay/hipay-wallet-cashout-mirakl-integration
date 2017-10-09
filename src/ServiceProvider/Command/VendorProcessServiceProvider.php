<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Vendor\ProcessCommand as VendorProcessCommand;

class VendorProcessServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.vendor.process'] = $app->share(
            function ($app) {

            return new VendorProcessCommand(
                $app['monolog'], $app['vendor.processor'], $app['batch.repository'],
                $app['hipay.parameters']['default.tmp.path']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}