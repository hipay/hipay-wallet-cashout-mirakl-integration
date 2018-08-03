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
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;

class TwigServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app->register(
            new SilexTwigServiceProvider()
        );

        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function ($twig, $app) {
                $twig->addFunction(
                    new \Twig_SimpleFunction(
                    'asset',
                    function ($asset) use ($app) {
                    return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
                }
                    )
                );
                return $twig;
            }
            )
        );

        $app->before(
            function (Request $request) use ($app) {
            $app['twig']->addGlobal('active', $request->get("_route"));
        }
        );
    }

    public function boot(Application $app)
    {

    }
}