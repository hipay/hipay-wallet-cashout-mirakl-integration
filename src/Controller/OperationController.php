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

use HiPay\Wallet\Mirakl\Integration\Entity\OperationRepository;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;

class OperationController extends AbstractTableController
{

    public function __construct(OperationRepository $repo, Serializer $serializer, Translator $translator)
    {
        parent::__construct($repo, $serializer, $translator);
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['CreatedDate'] = "";
            $data[$key]['balance'] = "";
            $data[$key]['balance'] = "";
        }

        return $data;
    }
}