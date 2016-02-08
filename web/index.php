<?php
/**
 * Main entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

const ROOT_PATH = __DIR__;

require_once __DIR__ . '/../app/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->post('/', function (Request $request) use ($app, $notificationHandler) {
    $notificationHandler->handleHipayNotification(urldecode($request->request->get('xml')));
    return new Response(null, 204);
});

$app->error(function (Exception $e) use ($app, $notificationHandler) {
    $notificationHandler->handleException($e);
    return new Response($e->getMessage());
});

$app->get('/', function () {
   return 'Hello World';
});

$app->run();
