<?php
/**
 * Initialize objects
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
$loader = require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\SerializerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use HiPay\Wallet\Mirakl\Integration\Configuration\DbConfiguration;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use HiPay\Wallet\Mirakl\Notification\Handler as NotificationHandler;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use HiPay\Wallet\Mirakl\Integration\Console\Style;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\MonologServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\ApiFactoryServiceProvider;
use HiPay\Wallet\Mirakl\Integration\ServiceProvider\ApiNotificationHandlerServiceProvider;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = new Silex\Application();

$paths = array(
    join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "src", "Entity"))
);

//Get the parameters
$parameters = new Accessor(__DIR__."/../config/parameters.yml");

$app['debug'] = $parameters['debug'];

/* * ***************
 * Doctrine initialization
 * ************** */

$dbConfiguration = new DbConfiguration($parameters);

$eventManager          = new Doctrine\Common\EventManager();
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

/* * ***************
 * Logger initialization
 * ************** */

$app->register(new MonologServiceProvider(),
               array(
    'log.file.path' => $parameters['log.file.path'],
    'db.logger.level' => $parameters['db.logger.level']
));

$app['hipay.event.dispatcher'] = function() use ($parameters, $app) {

    $eventDispatcher = new EventDispatcher();

    $eventDispatcher->addListener(
        ConsoleEvents::COMMAND,
        function (ConsoleCommandEvent $event) use ($parameters, $app) {
        $command = $event->getCommand();
        if ($parameters['debug'] && $command instanceof AbstractCommand) {
            $style = new Style($event->getInput(), $event->getOutput());
            $command->addDebugLogger($app['monolog'], $style);
        }
    }
    );

    return $eventDispatcher;
};

$app['vendors.repository'] = function() use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');
};

$app['operations.repository'] = function() use ($app) {
    $operationRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
    $operationRepository->setPublicLabelTemplate($parameters['label.public']);
    $operationRepository->setPrivateLabelTemplate($parameters['label.private']);
    $operationRepository->setWithdrawLabelTemplate($parameters['label.withdraw']);

    return $operationRepository;
};

$app['log.vendors.repository'] = function() use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');
};

$app['log.operations.repository'] = function() use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');
};

$app->register(new ApiFactoryServiceProvider(),
               array(
    'parameters' => $parameters
));

$app['api.hipay'] = function() use ($app) {
    return $app['api.hipay.factory']->getHiPay();
};

$app->register(new ApiNotificationHandlerServiceProvider(),
               array(
    'parameters' => $parameters
));

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(),
               array(
    'twig.path' => __DIR__.'/../views',
    'twig.options' => array(
        'cache' => __DIR__.'/../var/cache',
    ),
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['twig'] = $app->share($app->extend('twig',
                                        function($twig, $app) {
        $twig->addFunction(new \Twig_SimpleFunction('asset',
                                                    function ($asset) use ($app) {
            return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
        }));
        return $twig;
    }));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new Silex\Provider\HttpCacheServiceProvider(),
               array(
    'http_cache.cache_dir' => __DIR__.'/../var/cache/',
    'http_cache.esi' => null,
));

$app->register(new FormServiceProvider());

$app->register(new SerializerServiceProvider());

// set multilanguage support
$app->register(new TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['translator'] = $app->share($app->extend('translator',
                                              function($translator, $app) {
        $translator->addLoader('yaml', new YamlFileLoader());
        $translator->addResource('yaml', __DIR__.'/../app/locales/en.yml', 'en');
        $translator->addResource('yaml', __DIR__.'/../app/locales/fr.yml', 'fr');

        return $translator;
    }));

$app['translator']->setLocale($parameters['dashboard.locale']);
$app->before(function (Request $request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

return $app;
