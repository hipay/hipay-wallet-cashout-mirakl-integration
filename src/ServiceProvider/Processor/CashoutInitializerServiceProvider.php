<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Cashout\Initializer as CashoutInitializer;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;
use HiPay\Wallet\Mirakl\Integration\Model\TransactionValidator;

class CashoutInitializerServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['cashout.initializer'] = $app->share(
            function ($app) {

            $operatorAccount = new Vendor(
                $app['hipay.parameters']['account.operator.email'], null,
                $app['hipay.parameters']['account.operator.hipayId']
            );

            $technicalAccount = new Vendor(
                $app['hipay.parameters']['account.technical.email'], null,
                $app['hipay.parameters']['account.technical.hipayId']
            );

            $transactionValidator = new TransactionValidator();

            return new CashoutInitializer(
                $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'], $operatorAccount,
                $technicalAccount, $transactionValidator, $app['operations.repository'],
                $app['log.operations.repository'], $app['vendors.repository']
            );
        }
        );
    }

    public function boot(Application $app)
    {

    }
}