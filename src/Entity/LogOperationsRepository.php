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

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Notification\Model\LogOperationsInterface;
use HiPay\Wallet\Mirakl\Notification\Model\LogOperationsManagerInterface;

/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2016 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */
class LogOperationsRepository extends AbstractTableRepository implements LogOperationsManagerInterface
{

    /**
     * @param $miraklId
     * @param $hipayId
     * @param $amount
     * @param $statusTransferts
     * @param $statusWithDrawal
     * @param $message
     * @param $balance
     *
     * @return LogOperationsInterface
     */
    public function create($miraklId, $hipayId, $paymentVoucher, $amount, $balance)
    {
        $logOperations = new LogOperations($miraklId, $hipayId, $paymentVoucher, $amount, $balance);
        return $logOperations;
    }

    /**
     * @param array $logOperations
     * @return mixed
     */
    public function saveAll(array $logOperations)
    {
        foreach ($logOperations as $log) {
            $this->_em->persist($log);
        }

        $this->_em->flush();
    }

    /**
     * Insert more data if you want
     *
     * @param LogOperationsInterface $logOperations
     *
     * @return void
     */
    public function update(
    LogOperationsInterface $logOperations
    )
    {
        return;
    }

    /**
     * @param $shopId
     * @return LogOperationsInterface|null if not found
     */
    public function findByMiraklId($shopId)
    {
        return $this->findOneBy(array('miraklId' => $shopId));
    }

    /**
     * @param $shopId
     * @return LogOperationsInterface|null if not found
     */
    public function findByHipayId($shopId)
    {
        return $this->findOneBy(array('hipayId' => $shopId));
    }

    /**
     * @param LogOperationsInterface $logOperations
     * @return mixed
     */
    public function save($logOperations)
    {
        $this->_em->persist($logOperations);
        $this->_em->flush();
    }

    /**
     * @param $vendor
     * @return boolean
     */
    public function isValid(LogOperationsInterface $logOperations)
    {
        return true;
    }

    /**
     * Finds an operation.
     *
     * @param int $miraklId |null if operator
     * @param int $paymentVoucherNumber optional date to filter upon
     *
     * @return OperationInterface|null
     */
    public function findByMiraklIdAndPaymentVoucherNumber($miraklId, $paymentVoucherNumber)
    {
        return $this->findOneBy(
                array(
                    'miraklId' => $miraklId,
                    'paymentVoucher' => $paymentVoucherNumber
                )
        );
    }

    protected function getSelectString()
    {
        return 'a.miraklId, a.hipayId, a.amount, a.statusTransferts, a.statusWithDrawal, a.message, a.balance, a.dateCreated, a.paymentVoucher';
    }

    protected function getCountString()
    {
        return 'COUNT(a.id)';
    }

    protected function prepareAjaxRequest($queryBuilder, $search, $custom)
    {

        if (!empty($search)) {
            $queryBuilder->where(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('a.miraklId', '?1'), $queryBuilder->expr()->like('a.hipayId', '?1')
                    )
                )
                ->setParameter(1, '%'.$search.'%');
        }

        if (isset($custom["status-transfer"]) && $custom["status-transfer"] != -1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('a.statusTransferts', $custom["status-transfer"])
            );
        }
        if (isset($custom["status-withdraw"]) && $custom["status-withdraw"] != -1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('a.statusWithDrawal', $custom["status-withdraw"])
            );
        }


        return $queryBuilder;
    }
}