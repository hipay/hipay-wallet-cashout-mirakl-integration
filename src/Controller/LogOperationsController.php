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

use HiPay\Wallet\Mirakl\Integration\Entity\LogOperationsRepository;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\Status;

class LogOperationsController extends AbstractTableController
{

    public function __construct(
        LogOperationsRepository $repo,
        Serializer $serializer,
        Translator $translator,
        \Twig_Environment $twig
    ) {
        parent::__construct($repo, $serializer, $translator, $twig);
    }

    public function indexAction()
    {
        return $this->twig->render('pages/transferts.twig', array());
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {

            $data[$key]['statusTransferts'] = array(
                "status" => $logRow['statusTransferts'],
                "label" => $this->getStatusLabel($logRow['statusTransferts']),
                "button" => $this->getStatusMessage($logRow['statusTransferts'], $logRow)
            );

            $data[$key]['statusWithDrawal'] = array(
                "status" => $logRow['statusWithDrawal'],
                "label" => $this->getStatusLabel($logRow['statusWithDrawal']),
                "button" => $this->getStatusMessage($logRow['statusWithDrawal'], $logRow)
            );

            if ($logRow['dateCreated'] !== null) {
                $data[$key]['dateCreated'] = $logRow['dateCreated']->format('Y-m-d H:i:s');
            } else {
                $data[$key]['dateCreated'] = "";
            }
        }

        return $data;
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case Status::WITHDRAW_FAILED :
                return $this->translator->trans('withdraw.request.failed');
            case Status::WITHDRAW_NEGATIVE :
                return $this->translator->trans('withdraw.request.negative');
            case Status::WITHDRAW_CANCELED :
                return $this->translator->trans('withdraw.request.canceled');
            case Status::WITHDRAW_REQUESTED :
                return $this->translator->trans('withdraw.request.requested');
            case Status::TRANSFER_FAILED :
                return $this->translator->trans('transfer.request.failed');
            case Status::TRANSFER_NEGATIVE :
                return $this->translator->trans('transfer.request.negative');
            case Status::TRANSFER_SUCCESS :
                return $this->translator->trans('transfer.request.success');
            case Status::WITHDRAW_SUCCESS :
                return $this->translator->trans('withdraw.request.success');
            case Status::ADJUSTED_OPERATIONS :
                return $this->translator->trans('adjusted.operations');
            default:
                return "";
        }
    }

    private function getStatusMessage($status, $logRow)
    {
        switch ($status) {
            case Status::TRANSFER_SUCCESS:
            case Status::WITHDRAW_REQUESTED:
                return "";
            case Status::TRANSFER_FAILED:
            case Status::WITHDRAW_FAILED:
            case Status::WITHDRAW_CANCELED:
                return '<button type="button" class="btn btn-info btn-xs vendor-notice" data-container="body"'.
                    ' data-toggle="popover" data-placement="bottom" data-content="' .
                    $this->translator->trans($logRow["message"]) .
                    '" data-original-title="" title="" >' .
                    $this->translator->trans("show.message") .
                    '</button>';
            default:
                return "";
        }
    }
}
