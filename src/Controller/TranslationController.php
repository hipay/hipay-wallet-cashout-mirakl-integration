<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use Symfony\Component\HttpFoundation\Request;
use HiPay\Wallet\Mirakl\Api\HiPay;
use Silex\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;

class TranslationController
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function datatableAction(Request $request)
    {
        $i18n = array(
          "sProcessing" => $this->translator->trans("sProcessing"),
          "sSearch" => $this->translator->trans("sSearch"),
          "sLengthMenu" => $this->translator->trans("sLengthMenu"),
        );

        return $this->serializer->serialize($i18n, 'json');
    }
}