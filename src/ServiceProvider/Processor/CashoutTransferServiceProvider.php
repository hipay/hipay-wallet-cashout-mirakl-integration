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
use HiPay\Wallet\Mirakl\Cashout\Transfer as TransferProcessor;

class CashoutTransferServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['cashout.transfer.processor'] = $app->share(
            function ($app) {

                $technicalAccount = new Vendor(
                    $app['hipay.parameters']['account.technical.email'], null,
                    $app['hipay.parameters']['account.technical.hipayId']
                );

                $operatorAccount = new Vendor(
                    $app['hipay.parameters']['account.operator.email'], null,
                    $app['hipay.parameters']['account.operator.hipayId']
                );

                return new TransferProcessor(
                    $app['hipay.event.dispatcher'],
                    $app['monolog'],
                    $app['api.hipay.factory'],
                    $operatorAccount,
                    $technicalAccount,
                    $app['operations.repository'],
                    $app['log.operations.repository'],
                    $app['vendors.repository']
                );
            }
        );
    }

    public function boot(Application $app)
    {

    }
}