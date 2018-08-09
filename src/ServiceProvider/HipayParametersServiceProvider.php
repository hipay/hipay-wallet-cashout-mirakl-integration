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