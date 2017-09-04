<?php

/**
 * Initialize objects
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use HiPay\Wallet\Mirakl\Api\Factory as ApiFactory;
use HiPay\Wallet\Mirakl\Exception\Event\ThrowException;
use HiPay\Wallet\Mirakl\Exception\InvalidBankInfoException;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;
use HiPay\Wallet\Mirakl\Cashout\Initializer as CashoutInitializer;
use HiPay\Wallet\Mirakl\Cashout\Processor as CashoutProcessor;
use HiPay\Wallet\Mirakl\Notification\Handler as NotificationHandler;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use HiPay\Wallet\Mirakl\Integration\Configuration\DbConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\HiPayConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\MiraklConfiguration;
use HiPay\Wallet\Mirakl\Integration\Console\Style;
use HiPay\Wallet\Mirakl\Integration\Entity\OperationRepository;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;
use HiPay\Wallet\Mirakl\Integration\Entity\VendorRepository;
use HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository;
use HiPay\Wallet\Mirakl\Integration\Model\TransactionValidator;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use HiPay\Wallet\Mirakl\Integration\Handler\HipaySwiftMailerHandler;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use HiPay\Wallet\Mirakl\Integration\Handler\MonologDBHandler;

include dirname(__FILE__).'/../vendor/erusev/parsedown/Parsedown.php';

$paths = array(
    join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "src", "Entity"))
);

    const DEFAULT_LOG_PATH = "/var/log/hipay.log";

//Get the parameters
$parameters = new Accessor(__DIR__."/../config/parameters.yml");

$debug = $parameters['debug'];

$dbConfiguration = new DbConfiguration($parameters);

// the connection configuration
//$dbParams = array(
//    'driver' => $dbConfiguration->getDriver(),
//    'user' => $dbConfiguration->getUsername(),
//    'password' => $dbConfiguration->getPassword(),
//    'dbname' => $dbConfiguration->getDatabaseName(),
//    'host' => $dbConfiguration->getHost(),
//    'port' => $dbConfiguration->getPort()
//);

$eventManager          = new Doctrine\Common\EventManager();
$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$eventManager->addEventSubscriber($timestampableListener);

//$annotationMetadataConfiguration = Setup::createAnnotationMetadataConfiguration($paths, $debug, null, new ArrayCache(), false);
//$entityManager = EntityManager::create($dbParams, $annotationMetadataConfiguration, $eventManager);

$app->register(
    new DoctrineServiceProvider(),
    [
    'db.options' => array(
        'driver' => $dbConfiguration->getDriver(),
        'user' => $dbConfiguration->getUsername(),
        'password' => $dbConfiguration->getPassword(),
        'dbname' => $dbConfiguration->getDatabaseName(),
        'host' => $dbConfiguration->getHost(),
        'port' => $dbConfiguration->getPort()
    ),
    ]
);
$app->register(new DoctrineOrmServiceProvider(),
               [
    'orm.auto_generate_proxies' => $app['debug'],
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'HiPay\\Wallet\\Mirakl\\Integration\\Entity\\',
                'path' => $paths,
                'use_simple_annotation_reader' => false,
            ],
        ],
    ]
]);

$entityManager = $app["orm.em"];

$helperSet = ConsoleRunner::createHelperSet($entityManager);

$logger = new Logger("hipay");

$logFilePath = $parameters['log.file.path'] ? : DEFAULT_LOG_PATH;
$logger->pushHandler(new StreamHandler($logFilePath));

$swiftTransport = new Swift_SmtpTransport(
    $parameters['mail.host'], $parameters['mail.port'], $parameters['mail.security']
);


if (isset($parameters['mail.username']) && isset($parameters['mail.password'])) {
    $swiftTransport->setUsername($parameters['mail.username']);
    $swiftTransport->setPassword($parameters['mail.password']);
}

$mailer = new Swift_Mailer($swiftTransport);

$messageTemplate = new Swift_Message();
$messageTemplate->setSubject($parameters['mail.subject']);
$messageTemplate->setTo($parameters['mail.to']);
$messageTemplate->setFrom($parameters['mail.from']);
$messageTemplate->setCharset('utf-8');
$messageTemplate->setContentType("text/html");

//$logger->pushHandler(
//    new HipaySwiftMailerHandler($mailer, $messageTemplate, $parameters['email.logger.alert.level'])
//);

$logger->pushProcessor(new PsrLogMessageProcessor());

$logger->pushHandler(
    new MonologDBHandler($entityManager, $parameters['db.logger.level'])
);

/** @var ValidatorInterface $validator */
$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->getValidator();

$miraklConfiguration = new MiraklConfiguration($parameters);
$hipayConfiguration  = new HiPayConfiguration($parameters);

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addListener(
    ConsoleEvents::COMMAND,
    function (ConsoleCommandEvent $event) use ($parameters, $logger) {
    $command = $event->getCommand();
    if ($parameters['debug'] && $command instanceof AbstractCommand) {
        $style = new Style($event->getInput(), $event->getOutput());
        $command->addDebugLogger($logger, $style);
    }
}
);

$documentRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Document');

/** @var VendorRepository $vendorRepository */
$vendorRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');

$logVendorRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');

$apiFactory      = new ApiFactory($miraklConfiguration, $hipayConfiguration);

$app['api.hipay'] = function() use ($apiFactory) {
    return $apiFactory->getHiPay();
};

$vendorProcessor = new VendorProcessor(
    $eventDispatcher, $logger, $apiFactory, $vendorRepository, $documentRepository, $logVendorRepository
);

/** @var OperationRepository $operationRepository */
$operationRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
$operationRepository->setPublicLabelTemplate($parameters['label.public']);
$operationRepository->setPrivateLabelTemplate($parameters['label.private']);
$operationRepository->setWithdrawLabelTemplate($parameters['label.withdraw']);


$logOperationsRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');

$operatorAccount = new Vendor(
    $parameters['account.operator.email'], null, $parameters['account.operator.hipayId']
);

$technicalAccount = new Vendor(
    $parameters['account.technical.email'], null, $parameters['account.technical.hipayId']
);

$transactionValidator = new TransactionValidator();

$cashoutInitializer = new CashoutInitializer(
    $eventDispatcher, $logger, $apiFactory, $operatorAccount, $technicalAccount, $transactionValidator,
    $operationRepository, $logOperationsRepository, $vendorRepository
);

$cashoutProcessor = new CashoutProcessor(
    $eventDispatcher, $logger, $apiFactory, $operationRepository, $vendorRepository, $operatorAccount, $logOperationsRepository
);

$notificationHandler = new NotificationHandler($eventDispatcher, $logger, $operationRepository, $vendorRepository, $logVendorRepository,
                                               $apiFactory, $logOperationsRepository);
