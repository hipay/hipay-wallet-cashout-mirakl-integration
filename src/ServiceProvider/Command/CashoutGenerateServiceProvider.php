<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\GenerateCommand as CashoutGenerateCommand;

class CashoutGenerateServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.cashout.generate'] = $app->share(
            function ($app) {

            return new CashoutGenerateCommand(
                $app['monolog'], $app['cashout.initializer'], $app['hipay.parameters']['cycle.days'],
                $app['hipay.parameters']['cycle.hour'], $app['hipay.parameters']['cycle.minute'],
                $app['hipay.parameters']['cycle.interval.before'], $app['hipay.parameters']['cycle.interval.after'],
                $app['hipay.parameters']['cashout.transactionFilterRegex'], $app['batch.repository']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}