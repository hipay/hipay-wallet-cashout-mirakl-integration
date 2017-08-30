<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;
use Silex\Translator;

class LogVendorController
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

    public function ajaxAction(Request $request)
    {
        $first = $request->get('start');
        $limit = $request->get('length');

        $order        = $request->get('order');
        $columns      = $request->get('columns');
        $order        = end($order);
        $sortedColumn = $columns[$order["column"]]["data"];
        $search       = $request->get('search');

        $data = $this->repo->findAjax($first, $limit, $sortedColumn, $order["dir"], $search["value"]);
        $data = $this->prepareAjaxData($data);

        $returnArray = array(
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $this->repo->countAll(),
            'recordsFiltered' => $this->repo->countFiltered($search["value"]),
            'data' => $data
        );

        return $this->serializer->serialize($returnArray, 'json');
    }

    private function prepareAjaxData($data)
    {
        foreach ($data as $key => $logRow) {
            $data[$key]['date'] = $logRow['date']->format('Y-m-d H:m:s');
            $data[$key]['statusWalletAccount'] = array(
                    "status" => $logRow['statusWalletAccount'],
                    "label" => $this->getStatusWalletAccountString($logRow['statusWalletAccount'])
                );
            $data[$key]['document'] = array(
                    "nb" => $logRow['nbDoc'],
                    "miraklId" => $logRow['miraklId']
                );
            $data[$key]['status'] = array(
                    "status" => $logRow['status'],
                    "label" => $this->getStatusString($logRow['status']),
                    "button" => $this->getStatusMessage($logRow)
                );

        }

        return $data;
    }

    private function getStatusMessage($logRow){
        switch($logRow['status']){
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

    private function getStatusWalletAccountString($statusWalletAccount){
        switch($statusWalletAccount){
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

    private function getStatusString($status){
        switch($status){
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