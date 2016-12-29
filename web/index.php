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
use HiPay\Wallet\Mirakl\Integration\Parameter\Accessor;

$app = new Silex\Application();

$app->post('/{anyplace}', function (Request $request) use ($app, $notificationHandler) {
    //Get the parameters
    $parameters = new Accessor(__DIR__ . "/../config/parameters.yml");
    $swiftTransport = new Swift_SmtpTransport(
        $parameters['mail.host'],
        $parameters['mail.port'],
        $parameters['mail.security']
    );
    if (isset($parameters['mail.username']) && isset($parameters['mail.password'])) {
        $swiftTransport->setUsername($parameters['mail.username']);
        $swiftTransport->setPassword($parameters['mail.password']);
    }
    $mailer = new Swift_Mailer($swiftTransport);
    $mailer_message = new Swift_Message();
    $notificationHandler->handleHipayNotification(rawurldecode($request->request->get('xml')), $parameters, $mailer, $mailer_message);
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
