<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;
use HiPay\Wallet\Mirakl\Integration\Controller\LogVendorController;
use HiPay\Wallet\Mirakl\Integration\Controller\LogOperationsController;
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

$app->get('/',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['twig']->render('pages/vendors.twig');
})->bind('vendors');

$app->get('/log-vendors-ajax',function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    return $app['log.vendors.controller']->ajaxAction($app['request']);
})->bind('log-vendors-ajax');


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


$app->get('/logs',
          function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    $message .= 'Suspendisse rutrum et mauris euismod faucibus.';
    $message .= 'Vivamus eu sapien lacus. ';
    $message .= 'Sed sit amet nunc efficitur risus bibendum bibendum.';
    $message .= 'Pellentesque eu lectus sodales, pharetra libero id, condimentum urna.';
    $rows    = array(
        array('date' => '27/03/2017 12:43:23', 'type_info' => 'WARNING', 'error_message' => "Error Bank info is empty",
            'action' => 'Création Wallet', 'mirakl_id' => '44356', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '27/03/2017 12:43:23', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44355', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '26/03/2017 12:43:23', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44354', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '26/03/2017 12:43:23', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44353', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '26/03/2017 12:43:23', 'type_info' => 'WARNING', 'error_message' => "Error Bank info is empty",
            'action' => 'Création Wallet', 'mirakl_id' => '44356', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '25/03/2017 12:50:23', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44351', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '25/03/2017 12:32:21', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44350', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '25/03/2017 12:30:53', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44349', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '24/03/2017 16:45:33', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44348', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '24/03/2017 16:43:23', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44347', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        array('date' => '24/03/2017 16:40:13', 'type_info' => 'INFO', 'error_message' => "", 'action' => 'Création Wallet',
            'mirakl_id' => '44346', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
    );

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
