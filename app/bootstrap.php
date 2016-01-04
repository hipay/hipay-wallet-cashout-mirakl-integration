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

$isDevMode = DbConfiguration::getDebug();

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => DbConfiguration::getUsername(),
    'password' => DbConfiguration::getPassword(),
    'dbname'   => DbConfiguration::getDatabaseName(),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);