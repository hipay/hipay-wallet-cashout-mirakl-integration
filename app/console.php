<?php
/**
 * Console entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */


use Doctrine\ORM\Tools\Console\ConsoleRunner as ORMConsoleRunner;
use HiPay\Wallet\Mirakl\Integration\Command\Vendor\ProcessCommand as VendorProcessCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\ProcessCommand as CashoutProcessCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Cashout\GenerateCommand as CashoutGenerateCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Vendor\RecordCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Wallet\BankInfosCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Wallet\ListCommand;
use HiPay\Wallet\Mirakl\Integration\Command\Log\LogVendorsRecoverCommand;
use Symfony\Component\Console\Application;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;
use HiPay\Wallet\Mirakl\Cashout\Initializer as CashoutInitializer;
use HiPay\Wallet\Mirakl\Cashout\Processor as CashoutProcessor;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;
use HiPay\Wallet\Mirakl\Integration\Model\TransactionValidator;


$app = require_once __DIR__.'/../app/bootstrap.php';

$documentRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Document');

$vendorRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');

$logVendorRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');

$vendorProcessor = new VendorProcessor(
    $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'], $app['vendors.repository'], $documentRepository, $app['log.vendors.repository']
);

$logOperationsRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');

$operatorAccount = new Vendor(
    $parameters['account.operator.email'], null, $parameters['account.operator.hipayId']
);

$technicalAccount = new Vendor(
    $parameters['account.technical.email'], null, $parameters['account.technical.hipayId']
);

$transactionValidator = new TransactionValidator();

$cashoutInitializer = new CashoutInitializer(
    $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'], $operatorAccount, $technicalAccount, $transactionValidator,
    $app['operations.repository'], $app['log.operations.repository'], $app['vendors.repository']
);

$cashoutProcessor = new CashoutProcessor(
    $app['hipay.event.dispatcher'], $app['monolog'], $app['api.hipay.factory'], $app['operations.repository'], $app['vendors.repository'], $operatorAccount,
    $app['log.operations.repository']
);


$helperSet = ORMConsoleRunner::createHelperSet($app["orm.em"]);

$console = new Application("Hipay Mirakl Connector Console");

$console->setHelperSet($helperSet);

$console->setCatchExceptions(true);

$console->setDispatcher($app['hipay.event.dispatcher']);

ORMConsoleRunner::addCommands($console);

$batchManager = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Batch');
$vendorManager = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');
$logVendorsManager = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');

$vendorProcessCommand = new VendorProcessCommand(
    $app['monolog'],
    $vendorProcessor,
    $batchManager,
    $parameters['default.tmp.path']
);

$cashoutGenerateCommand = new CashoutGenerateCommand(
    $app['monolog'],
    $cashoutInitializer,
    $parameters['cycle.days'],
    $parameters['cycle.hour'],
    $parameters['cycle.minute'],
    $parameters['cycle.interval.before'],
    $parameters['cycle.interval.after'],
    $parameters['cashout.transactionFilterRegex']
);

$cashoutProcessCommand = new CashoutProcessCommand(
    $app['monolog'],
    $batchManager,
    $cashoutProcessor
);

$vendorRecordCommand = new RecordCommand($app['monolog'], $vendorProcessor);

$listCommand = new ListCommand(
    $app['monolog'],
    $vendorProcessor,
    $parameters['hipay.merchantGroupId']
);

$bankInfoCommand = new BankInfosCommand(
    $app['monolog'],
    $vendorProcessor,
    $vendorRepository,
    $operatorAccount,
    $technicalAccount
);

$logVendorsRecoverCommand = new LogVendorsRecoverCommand($app['monolog'], $vendorProcessor, $batchManager, $vendorManager, $logVendorsManager);

$commands = array(
    $vendorProcessCommand,
    $vendorRecordCommand,
    $cashoutProcessCommand,
    $cashoutGenerateCommand,
    $listCommand,
    $bankInfoCommand,
    $logVendorsRecoverCommand
);

$console->addCommands($commands);

$console->run();
