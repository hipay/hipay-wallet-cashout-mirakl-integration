<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2016 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsManagerInterface;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;

/**
 * Class LogVendorsRepository
 *
 */
class LogVendorsRepository extends EntityRepository implements LogVendorsManagerInterface
{
    /**
     * @param $miraklId
     * @param $hipayId
     * @param $status,
     * @param $message,
     * @param $nbDoc,
     * @param $date
     *
     * @return LogVendorsInterface
     */
    public function create(
        $miraklId,
        $hipayId,
        $status,
        $message,
        $nbDoc,
        $date
    )
    {

        $logVendor = new LogVendors($miraklId, $hipayId, $status, $message, $nbDoc, $date);
        return $logVendor;
    }

    /**
     * @param array $logVendors
     * @return mixed
     */
    public function saveAll(array $logVendors)
    {
        foreach ($logVendors as $logVendor) {
            $this->_em->persist($logVendor);
        }

        $this->_em->flush();
    }

    /**
     * Insert more data if you want
     *
     * @param LogVendorsInterface $logVendor
     * @param array $logData
     *
     * @return void
     */
    public function update(
        LogVendorsInterface $vendor,
        array $logData
    )
    {
        return;
    }

    /**
     * @param $shopId
     * @return LogVendorsInterface|null if not found
     */
    public function findByMiraklId($shopId)
    {
        return $this->findOneBy(array('miraklId' => $shopId));
    }

    /**
     * @param $shopId
     * @return LogVendorsInterface|null if not found
     */
    public function findByHipayId($shopId)
    {
        return $this->findOneBy(array('hipayId' => $shopId));
    }

    /**
     * @param LogVendorsInterface $logVendors
     * @return mixed
     */
    public function save($logVendors)
    {
        $this->_em->persist($logVendors);
        $this->_em->flush();
    }

    /**
     * @param $vendor
     * @return boolean
     */
    public function isValid(LogVendorsInterface $logVendors)
    {
        return true;
    }
}