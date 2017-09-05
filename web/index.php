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
$loader = require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Silex\Provider\SerializerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app          = new Silex\Application();
$app['debug'] = true;

require_once __DIR__.'/../app/bootstrap.php';

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

require __DIR__.'/../app/controllers.php';

$app['http_cache']->run();
