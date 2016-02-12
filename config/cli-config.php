<?php
/**
 * File cli-config.php
 * Doctrine configuration
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use HiPay\Wallet\Mirakl\Integration\Configuration\DbConfiguration;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;

$loader = require __DIR__ . '/../vendor/autoload.php';

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
    'host'     => $dbConfiguration->getHost(),
    'port'     => $dbConfiguration->getPort()
);

$eventManager = new Doctrine\Common\EventManager();
$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$eventManager->addEventSubscriber($timestampableListener);
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
$annotationMetadataConfiguration = Setup::createAnnotationMetadataConfiguration($paths, $debug, null, new ArrayCache(), false);
$entityManager = EntityManager::create($dbParams, $annotationMetadataConfiguration, $eventManager);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);