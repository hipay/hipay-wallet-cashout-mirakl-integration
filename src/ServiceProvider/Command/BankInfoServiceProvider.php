<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Wallet\BankInfosCommand;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;

class BankInfoServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.bank.info'] = $app->share(
            function ($app) {

            $operatorAccount = new Vendor(
                $app['hipay.parameters']['account.operator.email'], null,
                $app['hipay.parameters']['account.operator.hipayId']
            );

            $technicalAccount = new Vendor(
                $app['hipay.parameters']['account.technical.email'], null,
                $app['hipay.parameters']['account.technical.hipayId']
            );

            return new BankInfosCommand(
                $app['monolog'], $app['vendor.processor'], $app['vendors.repository'], $operatorAccount,
                $technicalAccount
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}