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

    $xml = rawurldecode($request->request->get('xml'));
    $date = new DateTime((string)$xml->result->date.' '.(string)$xml->result->time);
    $hipayId = (int) $xml->result->account_id;

    $response_notif = $notificationHandler->handleHipayNotification($xml);

    if ( !$response_notif && !is_null($mailer) && !is_null($mailer_message) ) {
        // init email content with response API
        $body = '
            <p><b>Operation - ' . (string) $xml->result->operation . '</b></p>
            <p>Informations:</p>
            <ul>
                <li>Status: ' . $xml->result->status . '</li>
                <li>Message: ' . $xml->result->message . '</li>
                <li>Date: ' . $date->format('Y-m-d H:i:s') . '</li>
                <li>Document type: ' . $xml->result->document_type . '</li>
                <li>Document type label: ' . $xml->result->document_type_label . '</li>
                <li>Account ID: ' . $hipayId . '</li>
            </ul>';

        $mailer_message->setSubject('[' . $parameters['mail.subject'] . ' - ' . $hipayId . '] ' . (string) $xml->result->operation);
        $mailer_message->setTo($parameters['mail.to']);
        $mailer_message->setFrom($parameters['mail.from']);
        $mailer_message->setCharset('utf-8');
        $mailer_message->setContentType("text/html");
        $mailer_message->setBody($body);
        $mailer->send($mailer_message);
    }


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
