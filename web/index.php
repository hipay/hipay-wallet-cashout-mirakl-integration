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

require_once __DIR__ . '/../app/bootstrap.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', trim($app['request']->getBasePath()), ltrim($asset, '/'));
    }));
    return $twig;
}));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider());

$app->before(function (Request $request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

$app->get('/', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }
    return $app['twig']->render('pages/vendors.twig');

})->bind('vendors');

$app->get('/transferts', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }
    return $app['twig']->render('pages/transferts.twig');

})->bind('transferts');

$app->get('/logs', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }
    return $app['twig']->render('pages/logs.twig');

})->bind('logs');

$app->get('/logout', function() use ($app) {
    $app['session']->set('user', null);
    return $app->redirect($app["url_generator"]->generate("login"));

})->bind('logout');

/*
 * settings page
 */
$app->match('/settings', function(Request $request) use ($app) {

    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    $sent = false;

    //Could use short array [] but have used long arrays in other parts
    $default = array(
        'setting_field' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $default)
        ->add('setting_field', 'text', array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
            'attr' => array('class' => 'form-control', 'placeholder' => 'Settings')
        ))
        ->add('send', 'submit', array(
            'attr' => array('class' => 'btn btn-default')
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        $sent = true;
    }
    return $app['twig']->render('pages/settings.twig', array('form' => $form->createView(), 'sent' => $sent));

})->bind('settings');

/*
 * Login page
 */
$app->match('/login', function(Request $request) use ($app) {

    if (null !== $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("vendors"));
    }

    $parameters = new Accessor(__DIR__ . "/../config/parameters.yml");
    $sent = false;

    //Could use short array [] but have used long arrays in other parts
    $default = array(
        'ws_login' => '',
        'ws_password' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $default)
        ->add('ws_login', 'text', array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
            'attr' => array('class' => 'form-control', 'placeholder' => 'Your API Login')
        ))
        ->add('ws_password', 'password', array(
            'constraints' => new Assert\NotBlank(),
            'attr' => array('class' => 'form-control', 'placeholder' => 'You API Password')
        ))
        ->add('send', 'submit', array(
            'attr' => array('class' => 'btn btn-default')
        ))
        ->getForm();

    $form->handleRequest($request);
    if ($form->isValid()) {
        $data = $form->getData();

        if ($data['ws_login'] === $parameters['hipay.wsLogin'] && $data['ws_password'] === $parameters['hipay.wsPassword']) {
            $app['session']->set('user', array('username' => $parameters['hipay.wsLogin']));
            return $app['twig']->render('pages/vendors.twig');
        }

        $sent = true;
    }
    return $app['twig']->render('pages/login.twig', array('form' => $form->createView(), 'sent' => $sent));

})->bind('login');

$app->post('/{anyplace}', function (Request $request) use ($app, $notificationHandler) {
    $notificationHandler->handleHipayNotification(rawurldecode($request->request->get('xml')));
    return new Response(null, 204);
})->assert("anyplace", ".*");

$app->error(function (Exception $e) use ($app, $notificationHandler) {
    $notificationHandler->handleException($e);
    return new Response($e->getMessage());
});

$app->run();
