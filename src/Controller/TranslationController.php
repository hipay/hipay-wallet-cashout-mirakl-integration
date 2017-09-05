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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class TranslationController
{
    protected $translator;
    protected $serializer;

    public function __construct(Translator $translator, Serializer $serializer)
    {
        $this->translator = $translator;
        $this->serializer = $serializer;
    }

    public function datatableAction(Request $request)
    {
        $i18n = array(
            "sProcessing" => $this->translator->trans("sProcessing"),
            "sSearch" => $this->translator->trans("sSearch"),
            "sLengthMenu" => $this->translator->trans("sLengthMenu"),
            "info" => $this->translator->trans("info"),
            "infoEmpty" => $this->translator->trans("infoEmpty"),
            "infoFiltered" => $this->translator->trans("infoFiltered"),
            "infoPostFix" => $this->translator->trans("infoPostFix"),
            "loadingRecords" => $this->translator->trans("loadingRecords"),
            "zeroRecords" => $this->translator->trans("zeroRecords"),
            "emptyTable" => $this->translator->trans("emptyTable"),
            "paginate" => array(
                "first" => $this->translator->trans("first"),
                "previous" => $this->translator->trans("previous"),
                "next" => $this->translator->trans("next"),
                "last" => $this->translator->trans("last"),
            ),
            "aria" => array(
                "sortAscending" => $this->translator->trans("sortAscending"),
                "sortDescending" => $this->translator->trans("sortDescending"),
            )
        );

        return $this->serializer->serialize($i18n, 'json');
    }
}