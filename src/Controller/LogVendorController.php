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

use HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;

class LogVendorController extends AbstractTableController
{

    public function __construct(
        LogVendorsRepository $repo,
        Serializer $serializer,
        Translator $translator,
        \Twig_Environment $twig
    ) {
        parent::__construct($repo, $serializer, $translator, $twig);
    }

    public function indexAction()
    {
        return $this->twig->render('pages/vendors.twig', array());
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['date'] = $logRow['date']->format('Y-m-d H:i:s');
            $data[$key]['statusWalletAccount'] = array(
                "status" => $logRow['statusWalletAccount'],
                "label" => $this->getStatusWalletAccountString($logRow['statusWalletAccount'])
            );

            if ($data[$key]['hipayId'] == -1 || $data[$key]['hipayId'] == null) {
                $data[$key]['document'] = array(
                    "nb" => $logRow['nbDoc'],
                    "miraklId" => $logRow['miraklId'],
                    "button" => ''
                );
            } else {
                $data[$key]['document'] = array(
                    "nb" => $logRow['nbDoc'],
                    "miraklId" => $logRow['miraklId'],
                    "button" => ' <a href="#" onclick="popup_vendor_detail(' .
                        $logRow['miraklId'] .
                        ');"> ' .
                        $this->translator->trans("show.details") .
                        '</a>'
                );
            }

            $data[$key]['status'] = array(
                "status" => $logRow['status'],
                "label" => $this->getStatusString($logRow['status']),
                "button" => $this->getStatusMessage($logRow)
            );

            $data[$key]['enabled'] = array(
                "enabled" => $logRow['enabled'],
                "label" => $this->getEnableLabel($logRow['enabled'])
            );
        }

        return $data;
    }

    private function getEnableLabel($enable){
        if($enable){
            return $this->translator->trans("enabled");
        }else{
            return $this->translator->trans("disabled");
        }
    }

    private function getStatusMessage($logRow)
    {
        switch ($logRow['status']) {
            case LogVendorsInterface::SUCCESS:
            case LogVendorsInterface::INFO:
                return "";
            case LogVendorsInterface::WARNING:
                return '<button type="button" class="btn btn-info btn-xs vendor-notice" data-container="body"'.
                    ' data-toggle="popover" data-placement="bottom" data-content="' .
                    $this->translator->trans($logRow["message"]) .
                    '" data-original-title="" title="" >' .
                    $this->translator->trans("show.message") .
                    '</button>';
            case LogVendorsInterface::CRITICAL:
                return '<button type="button" class="btn btn-danger btn-xs vendor-notice" data-container="body"'.
                    ' data-toggle="popover" data-placement="bottom" data-content="' .
                    $this->translator->trans($logRow["message"]) .
                    '" data-original-title="" title="" aria-describedby="popover846313">' .
                    $this->translator->trans("show.message") .
                    '</button>';
        }
    }

    private function getStatusWalletAccountString($statusWalletAccount)
    {
        switch ($statusWalletAccount) {
            case LogVendorsInterface::WALLET_CREATED:
                return $this->translator->trans("Created");
            case LogVendorsInterface::WALLET_NOT_CREATED:
                return $this->translator->trans("Not created");
            case LogVendorsInterface::WALLET_IDENTIFIED:
                return $this->translator->trans("Identified");
            case LogVendorsInterface::WALLET_NOT_IDENTIFIED:
                return $this->translator->trans("Not identified");
        }
    }

    private function getStatusString($status)
    {
        switch ($status) {
            case LogVendorsInterface::SUCCESS:
                return $this->translator->trans("Success");
            case LogVendorsInterface::INFO:
                return $this->translator->trans("Info");
            case LogVendorsInterface::WARNING:
                return $this->translator->trans("Warning");
            case LogVendorsInterface::CRITICAL:
                return $this->translator->trans("Critical");
        }
    }
}
