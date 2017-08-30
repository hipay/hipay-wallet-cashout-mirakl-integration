<?php

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

    public function ajaxAction(Request $request, $twig, $vendorRepository)
    {
        $vendor = $vendorRepository->findByMiraklId($request->get('id'));
        $documents = $this->apiHiPay->getDocuments($vendor);
        return $twig->render('partial/document.ajax.html.twig', array("documents" => $documents, "vendor" => $vendor));
    }
}