<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use Symfony\Component\Serializer\Serializer;
use HiPay\Wallet\Mirakl\Integration\Entity\LogGeneralRepository;
use Silex\Translator;

class LogGeneralController extends AbstractTableController
{
    public function __construct(LogGeneralRepository $repo, Serializer $serializer, Translator $translator)
    {
        parent::__construct($repo, $serializer, $translator);
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['createdAt'] = $logRow['createdAt']->format('Y-m-d H:i:s');
        }

        return $data;
    }
}