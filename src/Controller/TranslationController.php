<?php

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