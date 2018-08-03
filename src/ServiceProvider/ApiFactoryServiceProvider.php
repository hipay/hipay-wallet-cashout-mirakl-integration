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
                $miraklConfiguration = new MiraklConfiguration($app['hipay.parameters']);
                $hipayConfiguration = new HiPayConfiguration($app['hipay.parameters']);

                $apiFactory = new ApiFactory($miraklConfiguration, $hipayConfiguration);

                return $apiFactory;
            }
        );
    }

    public function boot(Application $app)
    {

    }
}