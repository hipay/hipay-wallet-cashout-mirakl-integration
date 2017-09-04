<?php

namespace HiPay\Wallet\Mirakl\Integration\Controller;

use HiPay\Wallet\Mirakl\Integration\Entity\VendorRepository;
use Symfony\Component\Serializer\Serializer;
use Silex\Translator;

class VendorController extends AbstractTableController
{
    protected $repo;
    protected $serializer;
    protected $translator;

    public function __construct(VendorRepository $repo, Serializer $serializer, Translator $translator)
    {
        $this->repo       = $repo;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }

    protected function prepareAjaxData($data)
    {
        return $data;
    }

}