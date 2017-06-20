<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorManagerInterface;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorInterface;

/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2016 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */
class LogGeneralRepository extends EntityRepository implements LogGeneralManagerInterface
{
    /**
     * @param $miraklId
     * @param $type
     * @param $action
     * @param $message
     * @param $date
     *
     * @return LogGeneralInterface
     */
    public function create(
        $miraklId,
        $type,
        $action,
        $message,
        $date
    )
    {

        $logGeneral = new LogGeneral($miraklId, $type, $action, $message, $date);
        return $logGeneral;
    }

    /**
     * @param array $vendors
     * @return mixed
     */
    public function saveAll(array $logGeneral)
    {
        foreach ($logGeneral as $log) {
            $this->_em->persist($log);
        }

        $this->_em->flush();
    }

    /**
     * Insert more data if you want
     *
     * @param LogGeneralInterface $logGeneral
     *
     * @return void
     */
    public function update(
        LogGeneralInterface $logGeneral
    )
    {
        return;
    }

    /**
     * @param $shopId
     * @return LogGeneralInterface|null if not found
     */
    public function findByMiraklId($shopId)
    {
        return $this->findOneBy(array('miraklId' => $shopId));
    }

    /**
     * @param LogGeneralInterface $logGeneral
     * @return mixed
     */
    public function save($logGeneral)
    {
        $this->_em->persist($logGeneral);
        $this->_em->flush();
    }

    /**
     * @param $logGeneral
     * @return boolean
     */
    public function isValid(LogGeneralInterface $logGeneral)
    {
        return true;
    }
}