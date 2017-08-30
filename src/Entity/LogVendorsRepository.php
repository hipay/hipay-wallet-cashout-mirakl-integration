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
    $miraklId, $hipayId, $login, $statusWalletAccount, $status, $message, $nbDoc
    )
    {
        $logVendor = new LogVendors($miraklId, $hipayId, $login, $statusWalletAccount, $status, $message, $nbDoc);
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
    LogVendorsInterface $vendor, array $logData
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

    public function findAjax($first, $limit, $sortedColumn, $dir, $search)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a.miraklId, a.login, a.hipayId, a.status, a.statusWalletAccount, a.message, a.nbDoc, a.date');
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
                        $queryBuilder->expr()->like('a.login', '?1'),
                        $queryBuilder->expr()->like('a.hipayId','?1')
                    )
                )
                ->setParameter(1, '%'.$search.'%');
        }
    }
}