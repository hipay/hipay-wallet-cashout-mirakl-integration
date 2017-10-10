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

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Silex\Provider\SerializerServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use HiPay\Wallet\Mirakl\Integration\Configuration\DbConfiguration;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\MonologServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\ApiFactoryServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\ApiNotificationHandlerServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\ApiHipayServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\HipayEventDispatcherServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\HipayParametersServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\SecurityServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\TwigServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\TranslationServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\RepositoriesServiceProvider;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new Silex\Application();

/* * ***************
 * Parameters initialization
 * ************** */

$app->register(new HipayParametersServiceProvider(), array('parameters.file' => __DIR__ . '/../config/parameters.yml'));

$app['debug'] = $app['hipay.parameters']['debug'];

/* * ***************
 * Doctrine initialization
 * ************** */

$paths = array(
    join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "src", "Entity"))
);

$dbConfiguration = new DbConfiguration($app['hipay.parameters']);

$eventManager = new Doctrine\Common\EventManager();
$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$eventManager->addEventSubscriber($timestampableListener);

//Load Doctrine service provider
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
//Load Doctrine ORM service provider
$app->register(
    new DoctrineOrmServiceProvider(),
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
    ]
);

/* * ***************
 * Logger initialization
 * ************** */

$app->register(
    new MonologServiceProvider(),
    array('log.file.path' => $app['hipay.parameters']['log.file.path'], 'db.logger.level' => $app['hipay.parameters']['db.logger.level'])
);

$app->register(new HipayEventDispatcherServiceProvider(), array('debug' => $app['hipay.parameters']['debug']));


/* * ***************
 * Repository initialization
 * ************** */

$app->register(new RepositoriesServiceProvider());

/* * ***************
 * API initialization
 * ************** */

$app->register(new ApiFactoryServiceProvider());

$app->register(new ApiHipayServiceProvider(), array());

$app->register(new ApiNotificationHandlerServiceProvider());

/* * ***************
 * Silex base initialization
 * ************** */

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new FormServiceProvider());

$app->register(new SerializerServiceProvider());


/* * ***************
 * Multilanguage initialization
 * ************** */

$app->register(new TranslationServiceProvider());

/* * ***************
 * Twig initialization
 * ************** */

$app->register(
    new TwigServiceProvider(),
    array(
        'twig.path' => __DIR__.'/../views',
         'twig.options' => array(
                'cache' => __DIR__.'/../var/cache'
            )
    )
);

/* * ***************
 * Security initialization
 * ************** */

$app->register(new SecurityServiceProvider(), array());

/* * ***************
 * Cache initialization
 * ************** */

$app->register(
    new Silex\Provider\HttpCacheServiceProvider(),
    array(
        'http_cache.cache_dir' => __DIR__ . '/../var/cache/',
        'http_cache.esi' => null,
    )
);

return $app;
