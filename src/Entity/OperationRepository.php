<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\ManagerInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\OperationInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\Status;
use Mustache_Engine;

class OperationRepository extends AbstractTableRepository implements ManagerInterface
{
    protected $privateLabelTemplate = "private {{miraklId}} – {{hipayId}} - {{cycleDate}}";

    protected $publicLabelTemplate = "public {{miraklId}} – {{hipayId}} - {{cycleDate}}";

    protected $withdrawLabelTemplate = "withdraw {{miraklId}} – {{hipayId}} - {{cycleDate}}";

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


    public function findVendorOperationsByPaymentVoucherId(OperationInterface $operation)
    {

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a')
            ->from($this->_entityName, 'a')
            ->where($queryBuilder->expr()->eq('a.paymentVoucher', $operation->getPaymentVoucher()))
            ->andWhere($queryBuilder->expr()->isNotNull('a.miraklId'));

        $query = $queryBuilder->getQuery();

        $results = $query->getSingleResult();

        return $results;

    }

    /**
     * @param $hipayId
     * @return array
     */
    public function findNegativeOperations($hipayId)
    {

        $status = new Status(Status::ADJUSTED_OPERATIONS);

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a')
            ->from($this->_entityName, 'a')
            ->where($queryBuilder->expr()->lte('a.amount', 0))
            ->andWhere($queryBuilder->expr()->eq('a.hipayId', $hipayId))
            ->andWhere($queryBuilder->expr()->neq('a.status', $status->getValue()));

        $query = $queryBuilder->getQuery();

        $results = $query->getResult();

        return $results;
    }


    /**
     * Finds operations
     *
     * @param Status $status status to filter upon
     * @param DateTime $date optional date to filter upon
     *
     * @return OperationInterface[]
     */
    public function findByStatusAndBeforeUpdatedAt(
        Status $status,
        DateTime $date
    ) {
        $criteria = new Criteria();
        $exprBuilder = new ExpressionBuilder();
        $criteria->where($exprBuilder->eq('status', $status->getValue()));
        $criteria->andWhere($exprBuilder->lte('updatedAt', $date));
        return $this->matching($criteria)->toArray();
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
        return $this->findBy(array("status" => $status->getValue()));
    }

    /**
     * Finds an operation
     *
     * @param int $miraklId
     * @param DateTime $date optional date to filter upon
     * @return OperationInterface|null
     */
    public function findByMiraklIdAndCycleDate(
        $miraklId,
        DateTime $date
    ) {
        return $this->findOneBy(array("miraklId" => $miraklId, 'cycleDate' => $date));
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
        return $this->findOneBy(array("withdrawId" => $withdrawalId));
    }

    /**
     * Create an operation.
     *
     * @param float $amount
     * @param DateTime $cycleDate
     * @param string $paymentVoucher
     * @param int $miraklId
     *
     * @return OperationInterface
     */
    public function create($amount, DateTime $cycleDate, $paymentVoucher, $miraklId = null)
    {
        $operation = new Operation();
        $operation->setMiraklId($miraklId);
        return $operation;
    }

    /**
     * Generate the public label of a transfer operation
     *
     * @param OperationInterface $operation
     *
     * @return string
     */
    public function generatePublicLabel(OperationInterface $operation)
    {
        $m = new Mustache_Engine;
        return $m->render($this->publicLabelTemplate, $this->getData($operation));
    }

    /**
     * Generate the private label of a transfer operation
     *
     * @param OperationInterface $operation
     *
     * @return string
     */
    public function generatePrivateLabel(OperationInterface $operation)
    {
        $m = new Mustache_Engine;
        return $m->render($this->privateLabelTemplate, $this->getData($operation));
    }

    /**
     * Generate the label of a withdraw operation
     *
     * @param OperationInterface $operation
     *
     * @return string
     */
    public function generateWithdrawLabel(OperationInterface $operation)
    {
        $m = new Mustache_Engine;
        return $m->render($this->withdrawLabelTemplate, $this->getData($operation));
    }

    /**
     * @param mixed $privateLabelTemplate
     */
    public function setPrivateLabelTemplate($privateLabelTemplate)
    {
        $this->privateLabelTemplate = $privateLabelTemplate ?
            $privateLabelTemplate : $this->privateLabelTemplate;
    }

    /**
     * @param mixed $publicLabelTemplate
     */
    public function setPublicLabelTemplate($publicLabelTemplate)
    {
        $this->publicLabelTemplate = $publicLabelTemplate ?
            $publicLabelTemplate : $this->publicLabelTemplate;
    }

    /**
     * @param mixed $withdrawLabelTemplate
     */
    public function setWithdrawLabelTemplate($withdrawLabelTemplate)
    {
        $this->withdrawLabelTemplate = $withdrawLabelTemplate ?
            $withdrawLabelTemplate : $this->withdrawLabelTemplate;
    }

    protected function getData(OperationInterface $operation)
    {
        return array(
            'miraklId' => $operation->getMiraklId(),
            'amount' => round($operation->getAmount(), 2),
            'hipayId' => $operation->getHipayId(),
            'cycleDate' => $operation->getCycleDate()->format('Y-m-d'),
            'cycleDateTime' => $operation->getCycleDate()->format(
                'Y-m-d H:i:s'
            ),
            'paymentVoucher' => $operation->getPaymentVoucher(),
            'cycleTime' => $operation->getCycleDate()->format('H:i:s'),
            'date' => date('Y-m-d'),
            'datetime' => date('Y-m-d H:i:s'),
            'time' => date('H:i:s'),
        );
    }

    /**
     * Finds an operation.
     *
     * @param int $miraklId |null if operator
     * @param int $paymentVoucherNumber optional date to filter upon
     *
     * @return OperationInterface|null
     */
    public function findByMiraklIdAndPaymentVoucherNumber(
        $miraklId,
        $paymentVoucherNumber
    ) {
        return $this->findBy(
            array(
                'miraklId' => $miraklId,
                'paymentVoucher' => $paymentVoucherNumber
            )
        );
    }

    protected function getSelectString()
    {
        return 'a.miraklId, a.hipayId, a.paymentVoucher, a.amount';
    }

    protected function getCountString()
    {
        return 'COUNT(a.miraklId)';
    }

    protected function prepareAjaxRequest($queryBuilder, $search, $custom)
    {

        if (!empty($search)) {
            $queryBuilder->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('a.miraklId', '?1'),
                    $queryBuilder->expr()->like('a.hipayId', '?1')
                )
            )
                ->setParameter(1, '%' . $search . '%');
        }

        return $queryBuilder;
    }
}
