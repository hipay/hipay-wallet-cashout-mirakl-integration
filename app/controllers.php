<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HiPay\Wallet\Mirakl\Integration\Controller\LogVendorController;
use HiPay\Wallet\Mirakl\Integration\Controller\LogOperationsController;
use HiPay\Wallet\Mirakl\Integration\Controller\LogGeneralController;
use HiPay\Wallet\Mirakl\Integration\Controller\DocumentController;
use HiPay\Wallet\Mirakl\Integration\Controller\TranslationController;
use HiPay\Wallet\Mirakl\Integration\Controller\BatchController;
use HiPay\Wallet\Mirakl\Integration\Controller\SettingController;
use HiPay\Wallet\Mirakl\Integration\Controller\LoginController;


/*****************
 * Log vendors Controller
 ****************/

$app['log.vendors.controller'] = function () use ($app) {
    return new LogVendorController($app['log.vendors.repository'], $app['serializer'], $app['translator']);
};

$app->get(
    '/log-vendors-ajax',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['log.vendors.controller']->ajaxAction($app['request']);
    }
)->bind('log-vendors-ajax');

$app->get(
    '/',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['twig']->render('pages/vendors.twig');
    }
)->bind('vendors');

/*****************
 * documents Controller
 ****************/

$app['documents.controller'] = function () use ($app) {
    return new DocumentController($app['api.hipay']);
};

$app->get(
    '/documents-ajax',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['documents.controller']->ajaxAction($app['request'], $app['twig'], $app['vendors.repository']);
    }
)->bind('documents-ajax');

/*****************
 * Translation Controller
 ****************/

$app['translation.controller'] = function () use ($app) {
    return new TranslationController($app['translator'], $app['serializer']);
};

$app->get(
    '/datatable/locale',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['translation.controller']->datatableAction($app['request']);
    }
)->bind('datatable-locale');


/*****************
 * Log Operations Controller
 ****************/

$app->get(
    '/transferts',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['twig']->render('pages/transferts.twig', array());
    }
)->bind('transferts');


$app['log.operations.controller'] = function () use ($app) {
    return new LogOperationsController($app['log.operations.repository'], $app['serializer'], $app['translator']);
};

$app->get(
    '/log-operations-ajax',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['log.operations.controller']->ajaxAction($app['request']);
    }
)->bind('log-operations-ajax');

/*****************
 * Logs Controller
 ****************/

$app['log.general.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogGeneral');
};

$app['log.general.controller'] = function () use ($app) {
    return new LogGeneralController($app['log.general.repository'], $app['serializer'], $app['translator']);
};

$app->get(
    '/log-general-ajax',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['log.general.controller']->ajaxAction($app['request']);
    }
)->bind('log-general-ajax');

$app->get(
    '/logs',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['twig']->render('pages/logs.twig', array());
    }
)->bind('logs');


/*****************
 * CSV export Controller
 ****************/

$app->get(
    '/logs.csv',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['log.general.controller']->exportCSVAction($app['request']);
    }
)->bind('logs.csv');

/*****************
 * Settings Controller
 ****************/

$app['batch.repository'] = function () use ($app) {
    return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Batch');
};

$app['batch.controller'] = function () use ($app) {
    return new BatchController($app['batch.repository'], $app['serializer'], $app['translator']);
};

$app->get(
    '/log-batch-ajax',
    function () use ($app) {
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['batch.controller']->ajaxAction($app['request']);
    }
)->bind('log-batch-ajax');

$app['settings.controller'] = function () use ($app) {
    return new SettingController($app['form.factory'], $app['twig'], $app['translator']);
};

$app->get(
    '/settings',
    function (Request $request) use ($app) {

        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['settings.controller']->indexAction();
    }
)->bind('settings');

$app->post(
    '/settings',
    function (Request $request) use ($app) {

        if (null === $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("login"));
        }

        return $app['settings.controller']->reRunAction($request);

    }
)->bind('settings-form');

/*
 * Login page
 */

$app['login.controller'] = function () use ($app) {
    return new LoginController($app['form.factory'], $app['hipay.parameters'], $app['twig'], $app['url_generator'], $app['session']);
};

$app->match('/login',function (Request $request) use ($app) {
        if (null !== $user = $app['session']->get('user')) {
            return $app->redirect($app["url_generator"]->generate("vendors"));
        }

        return $app['login.controller']->indexAction($request);
}
)->bind('login');

$app->get('/logout',
    function () use ($app) {
        return $app['login.controller']->logOutAction();
    }
)->bind('logout');

$app->post(
    '/{anyplace}',
    function (Request $request) use ($app) {
        $app['api.notification.handler']->handleHipayNotification(rawurldecode($request->request->get('xml')));
        return new Response(null, 204);
    }
)->assert("anyplace", ".*");

$app->error(
    function (Exception $e) use ($app) {
        $app['api.notification.handler']->handleException($e);
        return new Response($e->getMessage());
    }
);
