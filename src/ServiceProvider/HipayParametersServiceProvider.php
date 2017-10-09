<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;

class HipayParametersServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['hipay.parameters'] = $app->share(
            function ($app) {
                return new Accessor($app['parameters.file']);
            }
        );
    }

    public function boot(Application $app)
    {

    }
}