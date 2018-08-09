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
use Doctrine\ORM\Query;

/**
 * Class BatchRepository
 *
 */
class BatchRepository extends AbstractTableRepository
{

    /**
     * @param Batch $batch
     * @return mixed
     */
    public function save($batch)
    {
        $this->_em->persist($batch);
        $this->_em->flush();
    }

    protected function getSelectString()
    {
        return 'a.startedAt, a.name, a.endedAt, a.error';
    }

    protected function getCountString()
    {
        return 'COUNT(a.id)';
    }

    protected function prepareAjaxRequest($queryBuilder, $search, $custom)
    {
        return $queryBuilder;
    }
}