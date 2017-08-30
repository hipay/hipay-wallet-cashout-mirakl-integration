<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use HiPay\Wallet\Mirakl\Integration\Entity\LogOperationsRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Translator;

class LogOperationsController
{
    protected $repo;
    protected $serializer;
    protected $translator;

    public function __construct(LogOperationsRepository $repo, Serializer $serializer, Translator $translator)
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
            $data[$key]['dateCreated'] = $logRow['dateCreated']->format('Y-m-d H:m:s');
        }

        return $data;
    }

}