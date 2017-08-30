<?php
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
class LogOperationsRepository extends EntityRepository implements LogOperationsManagerInterface
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
    public function create(
        $miraklId,
        $hipayId,
        $amount,
        $statusTransferts,
        $statusWithDrawal,
        $message,
        $balance
    )
    {
        $logOperations = new LogOperations($miraklId, $hipayId, $amount, $statusTransferts, $statusWithDrawal, $message, $balance);
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

    public function findAjax($first, $limit, $sortedColumn, $dir, $search)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a.miraklId, a.hipayId, a.amount, a.statusTransferts, a.statusWithDrawal, a.message, a.balance, a.dateCreated, a.paymentVoucher');
        $this->prepateAjaxRequest($queryBuilder, $search);

        $queryBuilder->setFirstResult($first)
            ->setMaxResults($limit)
            ->orderBy('a.'.$sortedColumn, $dir)
        ;

        $query = $queryBuilder->getQuery();

        $results = $query->getResult();

        return $results;
    }

    public function countAll()
    {

        $count = $this->createQueryBuilder('post')
                ->select('COUNT(post)')
                ->getQuery()->getSingleScalarResult();

        return intval($count);
    }

    public function countFiltered($search)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('COUNT(a.miraklId)');
        $this->prepateAjaxRequest($queryBuilder, $search);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return intval($result);
    }

    private function prepateAjaxRequest(&$queryBuilder, $search)
    {
        $queryBuilder->from($this->_entityName, 'a');

        if (!empty($search)) {
            $queryBuilder->where(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('a.miraklId', '?1'),
                        $queryBuilder->expr()->like('a.hipayId', '?1')
                    )
                )
                ->setParameter(1, '%'.$search.'%');
        }
    }
}