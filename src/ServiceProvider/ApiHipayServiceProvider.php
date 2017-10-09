<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;

class ApiHipayServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['api.hipay'] = $app->share(
            function ($app) {
                return $app['api.hipay.factory']->getHiPay();
            }
        );
    }

    public function boot(Application $app)
    {

    }
}