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
use HiPay\Wallet\Mirakl\Exception\Event\ThrowException;
use HiPay\Wallet\Mirakl\Exception\InvalidBankInfoException;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;
use HiPay\Wallet\Mirakl\Cashout\Initializer as CashoutInitializer;
use HiPay\Wallet\Mirakl\Cashout\Processor as CashoutProcessor;
use HiPay\Wallet\Mirakl\Notification\Handler as NotificationHandler;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use HiPay\Wallet\Mirakl\Integration\Configuration\DbConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\FtpConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\HiPayConfiguration;
use HiPay\Wallet\Mirakl\Integration\Configuration\MiraklConfiguration;
use HiPay\Wallet\Mirakl\Integration\Console\Style;
use HiPay\Wallet\Mirakl\Integration\Entity\OperationRepository;
use HiPay\Wallet\Mirakl\Integration\Entity\Vendor;
use HiPay\Wallet\Mirakl\Integration\Entity\VendorRepository;
use HiPay\Wallet\Mirakl\Integration\Model\TransactionValidator;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
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
$annotationMetadataConfiguration = Setup::createAnnotationMetadataConfiguration($paths, $debug, null, null, false);
$entityManager = EntityManager::create($dbParams, $annotationMetadataConfiguration, $eventManager);

$helperSet = ConsoleRunner::createHelperSet($entityManager);

$logger = new Logger("hipay");

$logFilePath = $parameters['log.file.path'] ?: DEFAULT_LOG_PATH;
$logger->pushHandler(new StreamHandler($logFilePath));

$swiftTransport = new Swift_SmtpTransport(
    $parameters['mail.host'],
    $parameters['mail.port'],
    $parameters['mail.security']
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
$logger->pushHandler(
    new SwiftMailerHandler($mailer, $messageTemplate, Logger::CRITICAL)
);

$logger->pushProcessor(new PsrLogMessageProcessor());

/** @var ValidatorInterface $validator */
$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->getValidator();

$miraklConfiguration = new MiraklConfiguration($parameters);
$hipayConfiguration = new HiPayConfiguration($parameters);
$ftpConfiguration = new FtpConfiguration($parameters);

$eventDispatcher = new EventDispatcher();

$eventDispatcher->addListener(
    ConsoleEvents::COMMAND,
    function (ConsoleCommandEvent $event) use ($parameters, $logger){
        $command = $event->getCommand();
        if ($parameters['debug'] && $command instanceof AbstractCommand) {
            $style = new Style($event->getInput(), $event->getOutput());
            $command->addDebugLogger($logger, $style);
        }
    }
);

/** @var VendorRepository $vendorRepository */
$vendorRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');

$vendorProcessor = new VendorProcessor(
    $eventDispatcher,
    $logger,
    $miraklConfiguration,
    $hipayConfiguration,

    $ftpConfiguration,
    $vendorRepository
);
$vendorMail = clone $messageTemplate;
//Send mail to vendor in the case of invalid bank info
$vendorProcessor->addListener('invalid.bankInfo', function (ThrowException $event)
    use ($mailer, $vendorMail) {

    /** @var InvalidBankInfoException $ex */
    $ex = $event->getException();

    $vendor = $ex->getVendor();
    $vendorMail->setTo($vendor->getEmail());
    $vendorMail->setBody(
        "Your banking information in Mirakl is not in sync with HiPay data.\n
        Please either correct it in Mirakl or contact HiPay to change it."
    );
    $mailer->send($vendorMail);
});

/** @var OperationRepository $operationRepository */
$operationRepository = $entityManager->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
$operationRepository->setPublicLabelTemplate($parameters['label.public']);
$operationRepository->setPrivateLabelTemplate($parameters['label.private']);
$operationRepository->setWithdrawLabelTemplate($parameters['label.withdraw']);


$operatorAccount = new Vendor(
    $parameters['account.operator.email'],
    null,
    $parameters['account.operator.hipayId']
);

$technicalAccount = new Vendor(
    $parameters['account.technical.email'],
    null,
    $parameters['account.technical.hipayId']
);

$transactionValidator = new TransactionValidator();

$cashoutInitializer = new CashoutInitializer(
    $eventDispatcher,
    $logger,
    $miraklConfiguration,
    $hipayConfiguration,
    $operatorAccount,
    $technicalAccount,
    $transactionValidator,
    $operationRepository,
    $vendorRepository
);

$cashoutProcessor = new CashoutProcessor(
    $eventDispatcher,
    $logger,
    $miraklConfiguration,
    $hipayConfiguration,
    $operationRepository,
    $vendorRepository,
    $operatorAccount,
    $technicalAccount
);

$notificationHandler = new NotificationHandler($eventDispatcher, $logger,  $operationRepository, $vendorRepository);