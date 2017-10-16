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

use Doctrine\ORM\Tools\Console\ConsoleRunner as ORMConsoleRunner;
use Symfony\Component\Console\Application;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor\VendorProcessorServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor\CashoutInitializerServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Processor\CashoutProcessorServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\VendorProcessServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\CashoutGenerateServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\CashoutProcessServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\VendorRecordServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\VendorListServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\BankInfoServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\LogVendorsRecoverServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\Command\UpdateApplicationServiceProvider;

$app = require_once __DIR__ . '/../app/bootstrap.php';

/* * ***************
 * Console initialization
 * ************** */

$helperSet = ORMConsoleRunner::createHelperSet($app["orm.em"]);

$console = new Application("Hipay Mirakl Connector Console");

$console->setHelperSet($helperSet);

$console->setCatchExceptions(true);

$console->setDispatcher($app['hipay.event.dispatcher']);

ORMConsoleRunner::addCommands($console);

/* * ***************
 * Processor initialization
 * ************** */

$app->register(new VendorProcessorServiceProvider());

$app->register(new CashoutInitializerServiceProvider());

$app->register(new CashoutProcessorServiceProvider());

/* * ***************
 * Command initialization
 * ************** */

$app->register(new VendorProcessServiceProvider());

$app->register(new CashoutGenerateServiceProvider());

$app->register(new CashoutProcessServiceProvider());

$app->register(new VendorRecordServiceProvider());

$app->register(new VendorListServiceProvider());

$app->register(new BankInfoServiceProvider());

$app->register(new LogVendorsRecoverServiceProvider());

$app->register(new UpdateApplicationServiceProvider());

$commands = array(
    $app['command.vendor.process'],
    $app['command.vendor.record'],
    $app['command.cashout.process'],
    $app['command.cashout.generate'],
    $app['command.vendor.list'],
    $app['command.bank.info'],
    $app['command.log.vendors.recover'],
    $app['command.update.application']
);

$console->addCommands($commands);

$console->run();
