<?php
/**
 * Console entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
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

$app->register(new VendorProcessorServiceProvider(), array());

$app->register(new CashoutInitializerServiceProvider(), array());

$app->register(new CashoutProcessorServiceProvider(), array());

/* * ***************
 * Command initialization
 * ************** */

$app->register(new VendorProcessServiceProvider(), array());

$app->register(new CashoutGenerateServiceProvider(), array());

$app->register(new CashoutProcessServiceProvider(), array());

$app->register(new VendorRecordServiceProvider(), array());

$app->register(new VendorListServiceProvider(), array());

$app->register(new BankInfoServiceProvider(), array());

$app->register(new LogVendorsRecoverServiceProvider(), array());

$commands = array(
    $app['command.vendor.process'],
    $app['command.vendor.record'],
    $app['command.cashout.process'],
    $app['command.cashout.generate'],
    $app['command.vendor.list'],
    $app['command.bank.info'],
    $app['command.log.vendors.recover']
);

$console->addCommands($commands);

$console->run();
