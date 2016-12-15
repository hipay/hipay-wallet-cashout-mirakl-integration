<?php
/**
 * Main entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>, updated by Flavius Bindea - BFB Consulting
 * @copyright 2015 Smile, BFB Consulting
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
