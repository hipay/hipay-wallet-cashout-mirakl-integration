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