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
    /**
     * Creates instance of Swift_Message to be sent
     *
     * @param  string         $content formatted email body to be sent
     * @param  array          $records Log records that formed the content
     * @return \Swift_Message
     */
    protected function buildMessage($content, array $records)
    {
        $message = parent::buildMessage($content, $records);

        $Parsedown = new \Parsedown();
        $content = str_replace("_*","\r",$content);

        $html = $Parsedown->text(str_replace(str_repeat(" ",18),"\r",$content));

        $message->setBody($html);

        return $message;
    }
}