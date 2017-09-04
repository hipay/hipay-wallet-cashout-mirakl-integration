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

abstract class AbstractTableRepository extends EntityRepository
{

    /**
     * Build DQL query for datatable
     * @param type $first
     * @param type $limit
     * @param type $sortedColumn
     * @param type $dir
     * @param type $search
     * @param type $custom
     * @return type
     */
    public function findAjax($first, $limit, $sortedColumn, $dir, $search, $custom)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->getSelectString())
                     ->from($this->_entityName, 'a');
        $queryBuilder = $this->prepareAjaxRequest($queryBuilder, $search, $custom);

        $queryBuilder->setFirstResult($first)
            ->setMaxResults($limit)
            ->orderBy('a.'.$sortedColumn, $dir)
        ;

        $query = $queryBuilder->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     * Count all saved entities
     * @return type
     */
    public function countAll()
    {
        $query = $this->_em->createQueryBuilder()
            ->select($this->getCountString())
            ->from($this->_entityName, 'a')
            ->getQuery();
        $count = $query->getSingleScalarResult();
        return intval($count);
    }

    /**
     * Count all filtered entities
     * @param type $search
     * @param type $custom
     * @return type
     */
    public function countFiltered($search, $custom)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->getCountString())
            ->from($this->_entityName, 'a');
        $queryBuilder = $this->prepareAjaxRequest($queryBuilder, $search, $custom);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return intval($result);
    }

    /**
     * add specific filter from datatable
     */
    abstract protected function prepareAjaxRequest($queryBuilder, $search, $cutsom);

    /**
     * get SELECT
     */
    abstract protected function getSelectString();

    /**
     * get COUNT 
     */
    abstract protected function getCountString();
}