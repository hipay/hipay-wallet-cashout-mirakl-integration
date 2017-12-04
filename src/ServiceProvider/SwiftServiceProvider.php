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

class SwiftServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $swiftTransport = new \Swift_SmtpTransport(
            $app['hipay.parameters']['mail.host'],
            $app['hipay.parameters']['mail.port'],
            $app['hipay.parameters']['mail.security']
        );

        if (isset($app['hipay.parameters']['mail.username']) && isset($app['hipay.parameters']['mail.password'])) {
            $swiftTransport->setUsername($app['hipay.parameters']['mail.username']);
            $swiftTransport->setPassword($app['hipay.parameters']['mail.password']);
        }

        $app['swift.mailer'] = function () use ($app, $swiftTransport) {
            return new \Swift_Mailer($swiftTransport);
        };
    }

    public function boot(Application $app)
    {

    }
}