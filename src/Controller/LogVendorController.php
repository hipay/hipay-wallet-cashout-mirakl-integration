<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;

class LogVendorController extends AbstractTableController
{
    protected $repo;
    protected $serializer;
    protected $translator;

    public function __construct(LogVendorsRepository $repo, Serializer $serializer, Translator $translator)
    {
        $this->repo       = $repo;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }

    protected function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['date']                = $logRow['date']->format('Y-m-d H:i:s');
            $data[$key]['statusWalletAccount'] = array(
                "status" => $logRow['statusWalletAccount'],
                "label" => $this->getStatusWalletAccountString($logRow['statusWalletAccount'])
            );
            $data[$key]['document']            = array(
                "nb" => $logRow['nbDoc'],
                "miraklId" => $logRow['miraklId']
            );
            $data[$key]['status']              = array(
                "status" => $logRow['status'],
                "label" => $this->getStatusString($logRow['status']),
                "button" => $this->getStatusMessage($logRow)
            );
        }

        return $data;
    }

    private function getStatusMessage($logRow)
    {
        switch ($logRow['status']) {
            case LogVendorsInterface::SUCCESS:
            case LogVendorsInterface::INFO:
                return "";
            case LogVendorsInterface::WARNING:
                return '<button type="button" class="btn btn-info btn-xs vendor-notice" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$this->translator->trans($logRow["message"]).'" data-original-title="" title="" >'.$this->translator->trans("show.message").'</button>';
            case LogVendorsInterface::CRITICAL:
                return '<button type="button" class="btn btn-danger btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="'.$this->translator->trans($logRow["message"]).'" data-original-title="" title="" aria-describedby="popover846313">'.$this->translator->trans("show.message").'</button>';
            case LogVendorsInterface::CRITICAL:
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