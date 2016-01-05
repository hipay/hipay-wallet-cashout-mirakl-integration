<?php
/**
 * File bootstrap.php
 * Initialize the entity manager
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Hipay\SilexIntegration\Configuration\DbConfiguration;

$paths = array(
    join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "src", "Entity"))
);

$dbConfiguration = new DbConfiguration();
$isDevMode = $dbConfiguration->getDebug();

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => $dbConfiguration->getUsername(),
    'password' => $dbConfiguration->getPassword(),
    'dbname'   => $dbConfiguration->getDatabaseName(),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);