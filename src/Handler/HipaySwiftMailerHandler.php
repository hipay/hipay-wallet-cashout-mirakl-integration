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

namespace HiPay\Wallet\Mirakl\Integration\Handler;

use Monolog\Handler\SwiftMailerHandler;


class HipaySwiftMailerHandler extends SwiftMailerHandler
{

    protected $twig;

    /**
     * HipaySwiftMailerHandler constructor.
     * @param \Swift_Mailer $twig
     * @param \Swift_Mailer $mailer
     * @param int $message
     * @param bool $level
     */
    public function __construct($twig, \Swift_Mailer $mailer, $message, $level = Logger::ERROR)
    {
        parent::__construct($mailer, $message, $level);
        $this->twig = $twig;
    }

    /**
     * Creates instance of Swift_Message to be sent
     *
     * @param  string $content formatted email body to be sent
     * @param  array $records Log records that formed the content
     * @return \Swift_Message
     */
    protected function buildMessage($content, array $records)
    {
        $message = parent::buildMessage($content, $records);

        $body = $this->twig->render('mails/notification.twig', array("data" => $records[0]));
        $message->setSubject($message->getSubject(). " ". $records[0]['level_name']);
        $message->setBody($body);

        return $message;
    }
}