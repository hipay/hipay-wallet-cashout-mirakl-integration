<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Wallet\ListCommand;

class VendorListServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.vendor.list'] = $app->share(
            function ($app) {

            return new ListCommand(
                $app['monolog'], $app['vendor.processor'], $app['hipay.parameters']['hipay.merchantGroupId']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}