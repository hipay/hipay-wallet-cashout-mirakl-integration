<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\GenerateCommand as CashoutGenerateCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\ProcessCommand as CashoutProcessCommand;

class CashoutProcessServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.cashout.process'] = $app->share(
            function ($app) {

            return new CashoutProcessCommand(
                $app['monolog'], $app['batch.repository'], $app['cashout.processor']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}