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

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;
use HiPay\Wallet\Mirakl\Integration\Model\TransactionValidator;
use HiPay\Wallet\Mirakl\Cashout\Withdraw as WithdrawProcessor;

class CashoutWithdrawServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['cashout.withdraw.processor'] = $app->share(
            function ($app) {

                $operatorAccount = new Vendor(
                    $app['hipay.parameters']['account.operator.email'], null,
                    $app['hipay.parameters']['account.operator.hipayId']
                );

                $transactionValidator = new TransactionValidator();

                return new WithdrawProcessor(
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