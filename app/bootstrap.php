<?php
/**
 * Initialize objects
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Hipay\MiraklConnector\Vendor\Processor as VendorProcessor;
use Hipay\MiraklConnector\Cashout\Initializer as CashoutInitializer;
use Hipay\MiraklConnector\Cashout\Processor as CashoutProcessor;
use Hipay\MiraklConnector\Notification\Handler as NotificationHandler;
use Hipay\SilexIntegration\Command\AbstractCommand;
use Hipay\SilexIntegration\Configuration\DbConfiguration;
use Hipay\SilexIntegration\Configuration\FtpConfiguration;
use Hipay\SilexIntegration\Configuration\HipayConfiguration;
use Hipay\SilexIntegration\Configuration\MiraklConfiguration;
use Hipay\SilexIntegration\Console\Style;
use Hipay\SilexIntegration\Entity\OperationRepository;
use Hipay\SilexIntegration\Entity\Vendor;
use Hipay\SilexIntegration\Entity\VendorRepository;
use Hipay\SilexIntegration\Model\TransactionValidator;
use Hipay\SilexIntegration\Parameter\Accessor;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

$paths = array(
    join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "src", "Entity"))
);

const DEFAULT_LOG_PATH = "/var/log/hipay.log";

//Get the parameters
$parameters = new Accessor(__DIR__ . "/../config/parameters.yml");

$debug = $parameters['debug'];

$dbConfiguration = new DbConfiguration($parameters);

$isDevMode = $debug;

// the connection configuration
$dbParams = array(
    'driver'   => $dbConfiguration->getDriver(),
    'user'     => $dbConfiguration->getUsername(),
    'password' => $dbConfiguration->getPassword(),
    'dbname'   => $dbConfiguration->getDatabaseName(),
);

$eventManager = new Doctrine\Common\EventManager();
$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$eventManager->addEventSubscriber($timestampableListener);
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
$annotationMetadataConfiguration = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $annotationMetadataConfiguration, $eventManager);

$helperSet = ConsoleRunner::createHelperSet($entityManager);

$logger = new Logger("hipay");

$logFilePath = $parameters['log.file.path'] ?: DEFAULT_LOG_PATH;
$logger->pushHandler(new FilterHandler(new StreamHandler($logFilePath),Logger::WARNING));

$swiftTransport = new \Swift_SmtpTransport(
    $parameters['mail.host'],
    $parameters['mail.port'],
    $parameters['mail.security']
);


if (isset($parameters['mail.username']) && isset($parameters['mail.password'])) {
    $swiftTransport->setUsername($parameters['mail.username']);
    $swiftTransport->setPassword($parameters['mail.password']);
}

$mailer = new Swift_Mailer($swiftTransport);

$messageTemplate = new \Swift_Message();
$messageTemplate->setSubject($parameters['mail.subject']);
$messageTemplate->setTo($parameters['mail.to']);
$messageTemplate->setFrom($parameters['mail.from']);
$messageTemplate->setCharset('utf-8');
$logger->pushHandler(
    new FilterHandler(
        new SwiftMailerHandler($mailer, $messageTemplate),
        Logger::CRITICAL
    )
);

$logger->pushProcessor(new PsrLogMessageProcessor());

/** @var ValidatorInterface $validator */
$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->getValidator();

$miraklConfiguration = new MiraklConfiguration($parameters);
$hipayConfiguration = new HipayConfiguration($parameters);
$ftpConfiguration = new FtpConfiguration($parameters);

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addListener(
    ConsoleEvents::COMMAND,
    function (ConsoleCommandEvent $event, $eventName, $dispatcher) use ($parameters, $logger){
        $command = $event->getCommand();
        if ($parameters['debug'] && $command instanceof AbstractCommand) {
            $style = new Style($event->getInput(), $event->getOutput());
            $command->addDebugLogger($logger, $style);
        }
    }
);

/** @var VendorRepository $vendorRepository */
$vendorRepository = $entityManager->getRepository('Hipay\\SilexIntegration\\Entity\\Vendor');

$vendorProcessor = new VendorProcessor(
    $miraklConfiguration,
    $hipayConfiguration,
    $eventDispatcher,
    $logger,
    $ftpConfiguration,
    $vendorRepository
);

/** @var OperationRepository $operationRepository */
$operationRepository = $entityManager->getRepository('Hipay\\SilexIntegration\\Entity\\Operation');
$operationRepository->setPublicLabelTemplate($parameters['label.public']);
$operationRepository->setPrivateLabelTemplate($parameters['label.private']);
$operationRepository->setWithdrawLabelTemplate($parameters['label.withdraw']);


$operatorAccount = new Vendor(
    $parameters['account.operator.email'],
    false,
    $parameters['account.operator.hipayId']
);

$technicalAccount = new Vendor(
    $parameters['account.technical.email'],
    false,
    $parameters['account.technical.hipayId']
);

$transactionValidator = new TransactionValidator();

$cashoutInitializer = new CashoutInitializer(
    $miraklConfiguration,
    $hipayConfiguration,
    $eventDispatcher,
    $logger,
    $operatorAccount,
    $technicalAccount,
    $transactionValidator,
    $operationRepository,
    $vendorRepository
);

$cashoutProcessor = new CashoutProcessor(
    $miraklConfiguration,
    $hipayConfiguration,
    $eventDispatcher,
    $logger,
    $operationRepository,
    $vendorRepository,
    $operatorAccount
);

$notificationHandler = new NotificationHandler($operationRepository, $vendorRepository, $eventDispatcher);