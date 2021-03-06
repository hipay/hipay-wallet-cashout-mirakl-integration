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