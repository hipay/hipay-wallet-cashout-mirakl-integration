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

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command;

use Silex\ServiceProviderInterface;
use Silex\Application;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\TransactionStatusCommand as TransactionStatusCommand;

class CashoutTransactionStatusServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.cashout.transaction.status'] = $app->share(
            function ($app) {

                return new TransactionStatusCommand(
                    $app['monolog'], $app['batch.repository'], $app['cashout.transaction.status']
                );
            }
        );
    }

    public function boot(Application $app)
    {

    }
}
