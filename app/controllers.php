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
    return new LogVendorController(
        $app['log.vendors.repository'], $app['serializer'], $app['translator'], $app['twig']
    );
};

$app->get(
    '/dashboard/log-vendors-ajax',
    function () use ($app) {
        return $app['log.vendors.controller']->ajaxAction($app['request']);
    }
)->bind('log-vendors-ajax');

$app->get(
    '/dashboard/',
    function () use ($app) {
        return $app['log.vendors.controller']->indexAction();
    }
)->bind('vendors');

/*****************
 * documents Controller
 ****************/

$app['documents.controller'] = function () use ($app) {
    return new DocumentController($app['api.hipay']);
};

$app->get(
    '/dashboard/documents-ajax',
    function () use ($app) {
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
    '/dashboard/datatable/locale',
    function () use ($app) {
        return $app['translation.controller']->datatableAction($app['request']);
    }
)->bind('datatable-locale');


/*****************
 * Log Operations Controller
 ****************/

$app['log.operations.controller'] = function () use ($app) {
    return new LogOperationsController(
        $app['log.operations.repository'],
        $app['serializer'],
        $app['translator'],
        $app['twig']
    );
};

$app->get(
    '/dashboard/transferts',
    function () use ($app) {
        return $app['log.operations.controller']->indexAction();;
    }
)->bind('transferts');

$app->get(
    '/dashboard/log-operations-ajax',
    function () use ($app) {
        return $app['log.operations.controller']->ajaxAction($app['request']);
    }
)->bind('log-operations-ajax');

/*****************
 * Logs Controller
 ****************/

$app['log.general.controller'] = function () use ($app) {
    return new LogGeneralController(
        $app['log.general.repository'], $app['serializer'], $app['translator'], $app['twig']
    );
};

$app->get(
    '/dashboard/log-general-ajax',
    function () use ($app) {
        return $app['log.general.controller']->ajaxAction($app['request']);
    }
)->bind('log-general-ajax');

$app->get(
    '/dashboard/logs',
    function () use ($app) {
        return $app['log.general.controller']->indexAction();
    }
)->bind('logs');


/*****************
 * CSV export Controller
 ****************/

$app->get('/dashboard/logs.csv',
    function () use ($app) {
        return $app['log.general.controller']->exportCSVAction($app['request']);
    }
)->bind('logs.csv');

/*****************
 * Settings Controller
 ****************/

$app['batch.controller'] = function () use ($app) {
    return new BatchController($app['batch.repository'], $app['serializer'], $app['translator']);
};

$app->get('/dashboard/log-batch-ajax',
    function () use ($app) {
        return $app['batch.controller']->ajaxAction($app['request']);
    }
)->bind('log-batch-ajax');

$app['settings.controller'] = function () use ($app) {
    return new SettingController($app['form.factory'], $app['twig'], $app['translator'], $app['hipay.parameters'], $app['url_generator'], $app['hipay.parameters']);
};

$app->get(
    '/dashboard/settings',
    function (Request $request) use ($app) {

        return $app['settings.controller']->indexAction();
    }
)->bind('settings');

$app->post('/dashboard/settings',
    function (Request $request) use ($app) {

        return $app['settings.controller']->reRunAction($request);

    }
)->bind('settings-form');

/*****************
 * Settings Controller
 ****************/
$app->post(
    '/dashboard/update-integration',
    function (Request $request) use ($app) {

        return $app['settings.controller']->updateAction('integration');
    }
)->bind('update-integration');

$app->post(
    '/dashboard/update-library',
    function (Request $request) use ($app) {

        return $app['settings.controller']->updateAction('library');
    }
)->bind('update-library');

$app->post(
    '/dashboard/update-integration-process',
    function (Request $request) use ($app) {

        return $app['settings.controller']->updateIntegrationAjaxAction();
    }
)->bind('update-integration-ajax');

$app->post(
    '/dashboard/update-library-process',
    function (Request $request) use ($app) {

        return $app['settings.controller']->updateLibraryAjaxAction();
    }
)->bind('update-library-ajax');

/*****************
 * Login Controller
 ****************/

$app['login.controller'] = function () use ($app) {
    return new LoginController(
        $app['form.factory'],
        $app['twig'],
        $app['security.last_error']
    );
};

$app->match(
    '/dashboard/login',
    function (Request $request) use ($app) {
        return $app['login.controller']->indexAction($request);
    }
)->bind('login');

$app->post(
    '/{anyplace}',
    function (Request $request) use ($app) {
        $app['api.notification.handler']->handleHipayNotification(rawurldecode($request->request->get('xml')));
        return new Response(null, 204);
    }
)->assert("anyplace", ".*");

$app->get(
    '/{anyplace}',
    function (Request $request) use ($app) {
        return $app->redirect($app["url_generator"]->generate("vendors"));
    }
)->assert("anyplace", ".*");

$app->error(
    function (Exception $e) use ($app) {
        $app['api.notification.handler']->handleException($e);
        return new Response($e->getMessage());
    }
);
