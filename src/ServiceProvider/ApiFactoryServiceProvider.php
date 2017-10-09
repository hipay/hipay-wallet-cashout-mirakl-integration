<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Api\Factory as ApiFactory;
use HiPay\Wallet\Mirakl\Integration\Configuration\HiPayConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\MiraklConfiguration;

class ApiFactoryServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['api.hipay.factory'] = $app->share(
            function ($app) {
                $miraklConfiguration = new MiraklConfiguration($app['parameters']);
                $hipayConfiguration = new HiPayConfiguration($app['parameters']);

                $apiFactory = new ApiFactory($miraklConfiguration, $hipayConfiguration);

                return $apiFactory;
            }
        );
    }

    public function boot(Application $app)
    {

    }
}