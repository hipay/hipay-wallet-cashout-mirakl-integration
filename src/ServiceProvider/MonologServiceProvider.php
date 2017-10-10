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
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use HiPay\Wallet\Mirakl\Integration\Handler\MonologDBHandler;
use Monolog\Processor\PsrLogMessageProcessor;

class MonologServiceProvider implements ServiceProviderInterface
{
    const DEFAULT_LOG_PATH = "/var/log/hipay.log";

    public function register(Application $app)
    {
        $app['monolog.log.file'] = function () use ($app) {
            return $app['log.file.path'] ?: self::DEFAULT_LOG_PATH;
        };

        $app['monolog'] = $app->share(
            function ($app) {
                $logger = new Logger("hipay");

                $logger->pushHandler(new StreamHandler($app['monolog.log.file']));

                $logger->pushProcessor(new PsrLogMessageProcessor());
                // add database handler for Monolog
                $logger->pushHandler(
                    new MonologDBHandler($app["orm.em"], $app['db.logger.level'])
                );

                return $logger;
            }
        );
    }

    public function boot(Application $app)
    {

    }
}