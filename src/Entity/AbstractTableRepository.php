<?php

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;

abstract class AbstractTableRepository extends EntityRepository
{

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

    public function countAll()
    {
        $query = $this->_em->createQueryBuilder()
            ->select($this->getCountString())
            ->from($this->_entityName, 'a')
            ->getQuery();
        $count = $query->getSingleScalarResult();
        return intval($count);
    }

    public function countFiltered($search, $custom)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->getCountString())
            ->from($this->_entityName, 'a');
        $queryBuilder = $this->prepareAjaxRequest($queryBuilder, $search, $custom);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        return intval($result);
    }

    abstract protected function prepareAjaxRequest($queryBuilder, $search, $cutsom);

    abstract protected function getSelectString();

    abstract protected function getCountString();
}