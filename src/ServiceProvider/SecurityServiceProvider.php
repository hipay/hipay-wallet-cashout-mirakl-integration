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
use \Silex\Provider\SecurityServiceProvider as SilexSecurityServiceProvider;
use Symfony\Component\HttpFoundation\RequestMatcher;

class SecurityServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app->register(new SilexSecurityServiceProvider());


        $app['security.firewalls'] = [
            'login' => array(
                'pattern' => '^/dashboard/login$',
            ),
            'secured' => array(
                'pattern' => '^/dashboard.*$',
                'form' => array('login_path' => '/dashboard/login', 'check_path' => '/dashboard/login_check'),
                'logout' => array('logout_path' => '/dashboard/logout', 'invalidate_session' => true),
                'users' => array(
                    $app['hipay.parameters']['hipay.wsLogin'] => array(
                        'ROLE_ADMIN',
                        $app['security.encoder.digest']->encodePassword($app['hipay.parameters']['hipay.wsPassword'], '')
                    ),
                ),
            ),
            'notification' => array(
                'pattern' => new RequestMatcher('^.*$', null, 'POST'),
            ),
        ];
    }

    public function boot(Application $app)
    {

    }
}