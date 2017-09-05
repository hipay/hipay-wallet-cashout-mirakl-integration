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