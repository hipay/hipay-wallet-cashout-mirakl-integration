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
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\WithdrawCommand as WithdrawCommand;

class CashoutWithdrawServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['command.cashout.withdraw'] = $app->share(
            function ($app) {

                return new WithdrawCommand(
                    $app['monolog'], $app['batch.repository'], $app['cashout.withdraw.processor']
                );
            }
        );
    }

    public function boot(Application $app)
    {

    }
}