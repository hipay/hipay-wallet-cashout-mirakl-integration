<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2016 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Log\LogVendorsCountryCommand;

class LogVendorsCountryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['command.log.vendors.country'] = $app->share(
            function ($app) {
                return new LogVendorsCountryCommand(
                    $app['monolog'],
                    $app['vendor.processor'],
                    $app['batch.repository'],
                    $app['vendors.repository'],
                    $app['log.vendors.repository']
                );
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
