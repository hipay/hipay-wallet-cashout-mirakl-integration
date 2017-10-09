<?php
/**
 * Initialize objects
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\SerializerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
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

$app['vendors.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');
};

$app['document.repository'] = function () use ($app) {
    return $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Document');
};

$app['batch.repository'] = function () use ($app) {
    return $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Batch');
};

$app['operations.repository'] = function () use ($app) {
    $operationRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
    $operationRepository->setPublicLabelTemplate($app['hipay.parameters']['label.public']);
    $operationRepository->setPrivateLabelTemplate($app['hipay.parameters']['label.private']);
    $operationRepository->setWithdrawLabelTemplate($app['hipay.parameters']['label.withdraw']);

    return $operationRepository;
};

$app['log.vendors.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');
};

$app['log.operations.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');
};

/* * ***************
 * API initialization
 * ************** */

$app->register(
    new ApiFactoryServiceProvider(),
    array(
        'parameters' => $app['hipay.parameters']
    )
);

$app->register(new ApiHipayServiceProvider(), array());

$app->register(
    new ApiNotificationHandlerServiceProvider(),
    array(
        'parameters' => $app['hipay.parameters']
    )
);

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

$app->register(
    new TranslationServiceProvider(),
    array(
        'locale_fallbacks' => array('en'),
    )
);

$app['translator'] = $app->share(
    $app->extend(
        'translator',
        function ($translator, $app) {
            $translator->addLoader('yaml', new YamlFileLoader());
            $translator->addResource('yaml', __DIR__ . '/../app/locales/en.yml', 'en');
            $translator->addResource('yaml', __DIR__ . '/../app/locales/fr.yml', 'fr');

            return $translator;
        }
    )
);

$app['translator']->setLocale($app['hipay.parameters']['dashboard.locale']);

/* * ***************
 * Twig initialization
 * ************** */

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => __DIR__ . '/../views',
        'twig.options' => array(
            'cache' => __DIR__ . '/../var/cache',
        ),
    )
);

$app['twig'] = $app->share(
    $app->extend(
        'twig',
        function ($twig, $app) {
            $twig->addFunction(
                new \Twig_SimpleFunction(
                    'asset', function ($asset) use ($app) {
                    return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
                }
                )
            );
            return $twig;
        }
    )
);

$app->before(
    function (Request $request) use ($app) {
        $app['twig']->addGlobal('active', $request->get("_route"));
    }
);


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
