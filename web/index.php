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

$app->register(new FormServiceProvider());

$app->before(function (Request $request) use ($app) {
    $app['twig']->addGlobal('active', $request->get("_route"));
});

$app->get('/', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }
    $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    $message .= 'Suspendisse rutrum et mauris euismod faucibus.';
    $message .= 'Vivamus eu sapien lacus. ';
    $message .= 'Sed sit amet nunc efficitur risus bibendum bibendum.';
    $message .= 'Pellentesque eu lectus sodales, pharetra libero id, condimentum urna.';
    $rows = array(
        array('mirakl_id'=>'2789','login'=>'mirakl_vendorName_2789','message'=>$message, 'class'=>'danger', 'status'=>'Non créé', 'hipay_id'=>'-', 'document_sent'=>'-',),
        array('mirakl_id'=>'2788','login'=>'mirakl_vendorName_2788','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55999', 'document_sent'=>'5',),
        array('mirakl_id'=>'2787','login'=>'mirakl_vendorName_2787','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55998', 'document_sent'=>'3',),
        array('mirakl_id'=>'2786','login'=>'mirakl_vendorName_2786','message'=>'', 'class'=>'success', 'status'=>'Créé', 'hipay_id'=>'55997', 'document_sent'=>'5',),
        array('mirakl_id'=>'2785','login'=>'mirakl_vendorName_2785','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55996', 'document_sent'=>'4',),
        array('mirakl_id'=>'2784','login'=>'mirakl_vendorName_2784','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55995', 'document_sent'=>'3',),
        array('mirakl_id'=>'2783','login'=>'mirakl_vendorName_2783','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55994', 'document_sent'=>'4',),
        array('mirakl_id'=>'2782','login'=>'mirakl_vendorName_2782','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55993', 'document_sent'=>'4',),
        array('mirakl_id'=>'2781','login'=>'mirakl_vendorName_2781','message'=>$message, 'class'=>'danger', 'status'=>'Non créé', 'hipay_id'=>'-', 'document_sent'=>'-',),
        array('mirakl_id'=>'2780','login'=>'mirakl_vendorName_2780','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55992', 'document_sent'=>'5',),
        array('mirakl_id'=>'2779','login'=>'mirakl_vendorName_2779','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55991', 'document_sent'=>'5',),
    );

    return $app['twig']->render('pages/vendors.twig', array('rows' => $rows));

})->bind('vendors');

$app->get('/transferts', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    $message .= 'Suspendisse rutrum et mauris euismod faucibus.';
    $message .= 'Vivamus eu sapien lacus. ';
    $message .= 'Sed sit amet nunc efficitur risus bibendum bibendum.';
    $message .= 'Pellentesque eu lectus sodales, pharetra libero id, condimentum urna.';
    $rows = array(
        array('mirakl_id'=>'2789','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234543','transfert'=>'KO','withdrawal'=>'-','amount'=>'129,99€','balance'=>'0€','message'=>$message, 'class'=>'danger', 'status'=>'Non créé', 'hipay_id'=>'55888',),
        array('mirakl_id'=>'2788','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234542','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55999',),
        array('mirakl_id'=>'2787','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234541','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55998',),
        array('mirakl_id'=>'2786','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234540','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'success', 'status'=>'Créé', 'hipay_id'=>'55997',),
        array('mirakl_id'=>'2785','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234539','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55996',),
        array('mirakl_id'=>'2784','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234538','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55995',),
        array('mirakl_id'=>'2783','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234537','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55994',),
        array('mirakl_id'=>'2782','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234536','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55993',),
        array('mirakl_id'=>'2781','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234535','transfert'=>'OK','withdrawal'=>'KO','amount'=>'129,99€','balance'=>'20129,99€','message'=>$message, 'class'=>'danger', 'status'=>'Non créé', 'hipay_id'=>'55993',),
        array('mirakl_id'=>'2780','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234534','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'warning', 'status'=>'Non identifié', 'hipay_id'=>'55992',),
        array('mirakl_id'=>'2779','date_payment_cycle'=>'20/06/2017','id_payment_cycle'=>'234533','transfert'=>'OK','withdrawal'=>'OK','amount'=>'129,99€','balance'=>'20129,99€','message'=>'', 'class'=>'', 'status'=>'Identifié', 'hipay_id'=>'55991',),
    );

    return $app['twig']->render('pages/transferts.twig', array('rows' => $rows));

})->bind('transferts');

$app->get('/logs', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect($app["url_generator"]->generate("login"));
    }

    $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    $message .= 'Suspendisse rutrum et mauris euismod faucibus.';
    $message .= 'Vivamus eu sapien lacus. ';
    $message .= 'Sed sit amet nunc efficitur risus bibendum bibendum.';
    $message .= 'Pellentesque eu lectus sodales, pharetra libero id, condimentum urna.';
    $rows =
        array(
            array('date'=>'27/03/2017 12:43:23','type_info'=>'WARNING','error_message'=>"Error Bank info is empty",'action'=>'Création Wallet','mirakl_id'=>'44356','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'27/03/2017 12:43:23','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44355','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'26/03/2017 12:43:23','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44354','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'26/03/2017 12:43:23','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44353','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'26/03/2017 12:43:23','type_info'=>'WARNING','error_message'=>"Error Bank info is empty",'action'=>'Création Wallet','mirakl_id'=>'44356','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'25/03/2017 12:50:23','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44351','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'25/03/2017 12:32:21','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44350','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'25/03/2017 12:30:53','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44349','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'24/03/2017 16:45:33','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44348','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'24/03/2017 16:43:23','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44347','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
            array('date'=>'24/03/2017 16:40:13','type_info'=>'INFO','error_message'=>"",'action'=>'Création Wallet','mirakl_id'=>'44346','message'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras id vehicula eros, vel mollis dui. Pellentesque sapien sem, pulvinar vel massa non, varius venenatis nibh. Cras eget commodo augue, posuere dictum purus.',),
        );

    return $app['twig']->render('pages/logs.twig', array('rows' => $rows));

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
            //return $app['twig']->render('pages/vendors.twig');
            return $app->redirect($app["url_generator"]->generate("vendors"));
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
