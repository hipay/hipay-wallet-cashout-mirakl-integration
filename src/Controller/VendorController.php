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
        foreach ($data as $key => $logRow) {
            $data[$key]['statusWalletAccount'] = array(
                "status" => 1,
                "label" => $this->getStatusWalletAccountString($logRow)
            );
            $data[$key]['document']            = array(
                "nb" => 0,
                "miraklId" => $logRow['miraklId']
            );

            $data[$key]['login'] = $this->generateLogin($logRow);
        }

        return $data;
    }

    private function getStatusWalletAccountString($vendor)
    {
        if ($vendor["hipayIdentified"]) {
            return $this->translator->trans("Identified");
        } elseif (!$vendor["hipayIdentified"] && $vendor["hipayId"] != null) {
            return $this->translator->trans("Not identified");
        } elseif ($vendor["hipayId"] == null) {
            return $this->translator->trans("Not created");
        } else {
            return $this->translator->trans("Created");
        }
    }

    private function generateLogin($vendor){
        //return 'mirakl_' . preg_replace("/[^A-Za-z0-9]/", '',$vendor['shop_name']) . '_' . $vendor['shop_id'];
        return "";
    }
}