<?php

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class BatchRepository
 *
 */
class BatchRepository extends EntityRepository
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

}