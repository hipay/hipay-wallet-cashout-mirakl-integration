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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Translator;

abstract class AbstractTableController
{
    protected $repo;
    protected $serializer;
    protected $translator;

    public function __construct(EntityRepository $repo, Serializer $serializer, Translator $translator)
    {
        $this->repo       = $repo;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }

    /**
     * Retrieve data for datatable
     * @param Request $request
     * @return type
     */
    public function ajaxAction(Request $request)
    {
        $first = $request->get('start');
        $limit = $request->get('length');

        $order        = $request->get('order');
        $columns      = $request->get('columns');
        $order        = end($order);
        $sortedColumn = $columns[$order["column"]]["data"];
        $search       = $request->get('search');

        $params = $request->query->all();

        $data = $this->repo->findAjax($first, $limit, $sortedColumn, $order["dir"], $search["value"], $params);
        $data = $this->prepareAjaxData($data);

        $returnArray = array(
            'draw' => (int) $request->get('draw'),
            'recordsTotal' => $this->repo->countAll(),
            'recordsFiltered' => $this->repo->countFiltered($search["value"], $params),
            'data' => $data
        );

        return $this->serializer->serialize($returnArray, 'json');
    }

    abstract protected function prepareAjaxData($data);


}