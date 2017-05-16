<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use DateTime;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\OperationInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\Status;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Operation
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\OperationRepository")
 * @ORM\Table(name="operations")
 *
 */
class Operation implements OperationInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $miraklId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $hipayId;

    /**
     * @var float
     *
     * @ORM\Column(type="float", unique=false, nullable=false)
     */
    protected $amount;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $cycleDate;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $withdrawId;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $transferId;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $paymentVoucher;
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     *
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @var float
     * @ORM\Column(type="float", unique=false, nullable=true)
     */
    protected $withdrawnAmount;

    /**
     * @return float
     */
    public function getWithdrawnAmount()
    {
        return $this->withdrawnAmount;
    }

    /**
     * @return mixed
     */
    public function getMiraklId()
    {
        return $this->miraklId;
    }

    /**
     * @param mixed $miraklId
     * @return void
     */
    public function setMiraklId($miraklId)
    {
        $this->miraklId = $miraklId;
    }

    /**
     * @return mixed
     */
    public function getHipayId()
    {
        return $this->hipayId;
    }

    /**
     * @param mixed $hipayId
     * @return void
     */
    public function setHipayId($hipayId)
    {
        $this->hipayId = $hipayId;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCycleDate()
    {
        return $this->cycleDate;
    }

    /**
     * @param DateTime $cycleDate
     * @return void
     */
    public function setCycleDate(DateTime $cycleDate)
    {
        $this->cycleDate = $cycleDate;
    }

    /**
     * @return string
     */
    public function getWithdrawId()
    {
        return $this->withdrawId;
    }

    /**
     * @param string $hipayWithdrawId
     */
    public function setWithdrawId($hipayWithdrawId)
    {
        $this->withdrawId = $hipayWithdrawId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(Status $status)
    {
        $this->status = $status->getValue();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTransferId()
    {
        return $this->transferId;
    }

    /**
     * @param string $transferId
     */
    public function setTransferId($transferId)
    {
        $this->transferId = $transferId;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * @return string
     */
    public function getPaymentVoucher()
    {
        return $this->paymentVoucher;
    }

    /**
     * @param $paymentVoucher
     *
     * @return void
     */
    public function setPaymentVoucher($paymentVoucher)
    {
        $this->paymentVoucher = $paymentVoucher;
    }

    /**
     * @param $amount
     * @return void
     */
    public function setWithdrawnAmount($amount)
    {
        $this->withdrawnAmount = $amount;
    }
}