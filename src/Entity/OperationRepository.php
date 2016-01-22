<?php
namespace Hipay\SilexIntegration\Entity;

use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
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
        return $this->findOneBy(array("withdrawId" => $transactionId));
    }

    /**
     * Check if an operation is valid
     *
     * @param OperationInterface $operation
     *
     * @return bool
     */
    public function isValid(OperationInterface $operation)
    {
        return true;
    }

    /**
     * Finds operations
     *
     * @param Status $status status to filter upon
     *
     * @return OperationInterface[]
     */
    public function findByStatus(Status $status)
    {
        $this->findBy(array("status" => $status->getValue()));
    }

    /**
     * Finds an operation
     *
     * @param int $hipayId |false if operator
     * @param DateTime $date optional date to filter upon
     *
     * @return OperationInterface|null
     */
    public function findByHipayIdAndCycleDate(
        $hipayId,
        DateTime $date
    )
    {
        $criteria = new Criteria();
        $exprBuilder = new ExpressionBuilder();
        $criteria->where($exprBuilder->eq('hipayId', $hipayId));
        $criteria->andWhere($exprBuilder->eq('cycleDate', $date));
        $this->matching($criteria);
    }

    /**
     * Find an operation by transactionId
     *
     * @param $withdrawalId
     *
     * @return OperationInterface|null
     */
    public function findByWithdrawalId($withdrawalId)
    {
        $this->findOneBy(array("withdrawId" => $withdrawalId));
    }
}