<?php
/**
 * Main entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

use Symfony\Component\HttpFoundation\Request;
const ROOT_PATH = __DIR__;

require_once __DIR__ . '/../app/bootstrap.php';

$app = new Silex\Application();
$app->post('/', function (Request $request) use ($app, $notificationHandler) {
    $notificationHandler->handleHipayNotification($request->getContent());
    return 'Notification handled';
});

$app->get('/', function () {
   return 'Hello World';
});

$app->run();
