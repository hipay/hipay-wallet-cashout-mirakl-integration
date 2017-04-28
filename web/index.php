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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->post('/{anyplace}', function (Request $request) use ($app, $notificationHandler) {
    $notificationHandler->handleHipayNotification(rawurldecode($request->request->get('xml')));
    return new Response(null, 204);
})->assert("anyplace", ".*");

$app->error(function (Exception $e) use ($app, $notificationHandler) {
    $notificationHandler->handleException($e);
    return new Response($e->getMessage());
});

$app->get('/{anyplace}', function () {
   return 'Hello World';
})->assert("anyplace", ".*");

$app->run();
