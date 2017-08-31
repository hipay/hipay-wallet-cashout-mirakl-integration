<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use HiPay\Wallet\Mirakl\Integration\Controller\LogVendorController;
use HiPay\Wallet\Mirakl\Integration\Controller\VendorController;
use HiPay\Wallet\Mirakl\Integration\Controller\LogOperationsController;
use HiPay\Wallet\Mirakl\Integration\Controller\OperationController;
use HiPay\Wallet\Mirakl\Integration\Controller\LogGeneralController;
use HiPay\Wallet\Mirakl\Integration\Controller\DocumentController;
use HiPay\Wallet\Mirakl\Integration\Controller\TranslationController;
use Symfony\Component\Validator\Constraints as Assert;

$app['vendors.repository'] = function() use ($app){
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');
};

$app['log.vendors.repository'] = function() use ($app){
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');
};

$app['log.vendors.controller'] = function() use ($app) {
    return new LogVendorController($app['log.vendors.repository'], $app['serializer'], $app['translator']);
};

$app->get('/log-vendors-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['log.vendors.controller']->ajaxAction($app['request']);
})->bind('log-vendors-ajax');

$app['vendors.controller'] = function() use ($app) {
    return new VendorController($app['vendors.repository'], $app['serializer'], $app['translator']);
};

$app->get('/vendors-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['vendors.controller']->ajaxAction($app['request']);
})->bind('vendors-ajax');

$app->get('/',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['twig']->render('pages/vendors.twig');
})->bind('vendors');

$app['documents.controller'] = function() use ($app) {
    return new DocumentController($app['api.hipay']);
};

$app->get('/documents-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['documents.controller']->ajaxAction($app['request'], $app['twig'], $app['vendors.repository']);
})->bind('documents-ajax');


$app['translation.controller'] = function() use ($app) {
    return new TranslationController($app['translator']);
};

$app->get('/{_locale}/datatable/locale',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return$app['translation.controller']->datatableAction($app['request']);
})->bind('datatable-locale');


$app->get('/transferts',
          function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['twig']->render('pages/transferts.twig', array());
})->bind('transferts');


$app['log.operations.repository'] = function() use ($app){
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');
};

$app['log.operations.controller'] = function() use ($app) {
    return new LogOperationsController($app['log.operations.repository'], $app['serializer'], $app['translator']);
};

$app->get('/log-operations-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['log.operations.controller']->ajaxAction($app['request']);
})->bind('log-operations-ajax');

$app['operations.repository'] = function() use ($app){
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
};

$app['operations.controller'] = function() use ($app) {
    return new OperationController($app['operations.repository'], $app['serializer'], $app['translator']);
};

$app->get('/operations-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['operations.controller']->ajaxAction($app['request']);
})->bind('operations-ajax');


$app['log.general.repository'] = function() use ($app){
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogGeneral');
};

$app['log.general.controller'] = function() use ($app) {
    return new LogGeneralController($app['log.general.repository'], $app['serializer'], $app['translator']);
};

$app->get('/log-general-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['log.general.controller']->ajaxAction($app['request']);
})->bind('log-general-ajax');


$app->get('/logs',
          function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['twig']->render('pages/logs.twig', array('rows' => $rows));
})->bind('logs');

$app->get('/logout',
          function() use ($app) {
    $app['session']->set('user', null);
    return $app->redirect($app["url_generator"]->generate("login"));
})->bind('logout');

/*
 * settings page
 */
$app->match('/settings',
            function(Request $request) use ($app) {

    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    $sent = false;

    //Could use short array [] but have used long arrays in other parts
    $default = array(
        'setting_field' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $default)
        ->add('setting_field', 'text',
              array(
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
$app->match('/login',
            function(Request $request) use ($app) {

    if (null !== $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("vendors"));
    }

    $parameters = new Accessor(__DIR__."/../config/parameters.yml");
    $sent       = false;

    //Could use short array [] but have used long arrays in other parts
    $default = array(
        'ws_login' => '',
        'ws_password' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $default)
        ->add('ws_login', 'text',
              array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
            'attr' => array('class' => 'form-control', 'placeholder' => 'Your API Login')
        ))
        ->add('ws_password', 'password',
              array(
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
            //return $app['twig']->render('pages/vendors.twig');
            return $app->redirect($app["url_generator"]->generate("vendors"));
        }

        $sent = true;
    }
    return $app['twig']->render('pages/login.twig', array('form' => $form->createView(), 'sent' => $sent));
})->bind('login');

$app->post('/{anyplace}',
           function (Request $request) use ($app, $notificationHandler) {
    $notificationHandler->handleHipayNotification(rawurldecode($request->request->get('xml')));
    return new Response(null, 204);
})->assert("anyplace", ".*");

$app->error(function (Exception $e) use ($app, $notificationHandler) {
    $notificationHandler->handleException($e);
    return new Response($e->getMessage());
});
