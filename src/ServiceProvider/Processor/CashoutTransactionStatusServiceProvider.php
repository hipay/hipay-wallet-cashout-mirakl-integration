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

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor;

use HiPay\Wallet\Mirakl\Cashout\TransactionStatus as TransactionStatusProcessor;
use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;

class CashoutTransactionStatusServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['cashout.transaction.status'] = $app->share(
            function ($app) {

                $operatorAccount = new Vendor(
                    $app['hipay.parameters']['account.operator.email'], null,
                    $app['hipay.parameters']['account.operator.hipayId']
                );

                return new TransactionStatusProcessor(
                    $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'],
                    $app['operations.repository'], $app['vendors.repository'], $operatorAccount,
                    $app['log.operations.repository']
                );
            }
        );
    }

    public function boot(Application $app)
    {

    }
}
