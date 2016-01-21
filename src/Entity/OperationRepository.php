<?php
/**
 * File OperationRepository.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

namespace Hipay\SilexIntegration\Entity;


use DateTime;
use Doctrine\ORM\EntityRepository;
use Hipay\MiraklConnector\Cashout\Model\Operation\ManagerInterface;
use Hipay\MiraklConnector\Cashout\Model\Operation\OperationInterface;
use Hipay\MiraklConnector\Cashout\Model\Operation\Status;

class OperationRepository extends EntityRepository implements ManagerInterface
{

    /**
     * Save a batch of operation
     *
     * @param OperationInterface[] $operations
     * @return bool
     *
     */
    public function saveAll(array $operations)
    {
        foreach ($operations as $operation) {
            $this->_em->persist($operation);
        }

        $this->_em->flush();
    }

    /**
     * Save a single operation
     *
     * @param OperationInterface $operation
     *
     * @return bool
     */
    public function save($operation)
    {
        $this->_em->persist($operation);
        $this->_em->flush();
    }

    /**
     * Create an operation
     *
     * @param int $shopId the mirakl shop id |false if it is an operator operation
     *
     * @return OperationInterface
     */
    public function create($shopId)
    {
        $operation = new Operation();
        $operation->setMiraklId($shopId);
        return $operation;
    }

    /**
     * Check if an operation is savable
     *
     * @param OperationInterface $operation
     *
     * @return bool
     */
    public function isSavable(OperationInterface $operation)
    {
        // TODO: Implement isSavable() method.
    }

    /**
     * Finds operations
     *
     * @param Status $status status to filter upon
     * @param DateTime $date optional date to filter upon
     *
     * @return OperationInterface[]
     */
    public function findByStatusAndCycleDate(
        Status $status,
        DateTime $date = null
    )
    {
        // TODO: Implement findByStatusAndCycleDate() method.
    }

    /**
     * Find an operation by transactionId
     *
     * @param $transactionId
     * @return OperationInterface
     */
    public function findByTransactionId($transactionId)
    {
        // TODO: Implement findByTransactionId() method.
    }
}