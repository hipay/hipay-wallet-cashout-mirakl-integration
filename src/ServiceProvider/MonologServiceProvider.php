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
use HiPay\Wallet\Mirakl\Integration\Handler\HipaySwiftMailerHandler;

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

                $messageTemplate = $this->initSwiftMailer($app);

                $logger->pushHandler(
                    new HipaySwiftMailerHandler(
                        $app['twig'],
                        $app['swift.mailer'],
                        $messageTemplate,
                        $app['hipay.parameters']['email.logger.alert.level']
                    )
                );

                return $logger;
            }
        );
    }

    public function boot(Application $app)
    {

    }

    private function initSwiftMailer(Application $app)
    {

        $messageTemplate = new \Swift_Message();
        $messageTemplate->setSubject($app['hipay.parameters']['mail.subject']);
        $messageTemplate->setTo($app['hipay.parameters']['mail.to']);
        $messageTemplate->setFrom($app['hipay.parameters']['mail.from']);
        $messageTemplate->setCharset('utf-8');
        $messageTemplate->setContentType("text/html");

        return $messageTemplate;
    }
}