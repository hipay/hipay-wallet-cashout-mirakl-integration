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

class DocumentController
{
    protected $apiHiPay;

    public function __construct(HiPay $apiHiPay)
    {
        $this->apiHiPay = $apiHiPay;
    }

    /**
     * Get documents informations from Wallet API
     * @param Request $request
     * @param type $twig
     * @param type $vendorRepository
     * @return type
     */
    public function ajaxAction(Request $request, $twig, $vendorRepository)
    {
        $vendor = $vendorRepository->findByMiraklId($request->get('id'));
        $documents = $this->apiHiPay->getDocuments($vendor);
        return $twig->render('partial/document.ajax.html.twig', array("documents" => $documents, "vendor" => $vendor));
    }
}