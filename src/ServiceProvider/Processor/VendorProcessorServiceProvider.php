<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;

class VendorProcessorServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['vendor.processor'] = $app->share(
            function ($app) {
            return new VendorProcessor(
                $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'], $app['vendors.repository'],
                $app['document.repository'], $app['log.vendors.repository']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}