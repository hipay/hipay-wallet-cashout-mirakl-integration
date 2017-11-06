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

use DateTime;
use HiPay\Wallet\Mirakl\Notification\Model\LogOperationsInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LogOperations
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\LogOperationsRepository")
 * @ORM\Table(name="log_operations")
 */
class LogOperations implements LogOperationsInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=false, nullable=true)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $miraklId;

    /**
     * @var int The HiPay Wallet account ID
     *
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $hipayId;

    /**
     * @var string
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $paymentVoucher;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     *
     */
    protected $dateCreated;

    /**
     * @var float
     *
     * @ORM\Column(type="float", unique=false, nullable=false)
     */
    protected $amount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", unique=false, nullable=true)
     */
    protected $originAmount;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $statusTransferts;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $statusWithDrawal;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $balance;

    /**
     * Vendor constructor.
     * @param int $miraklId
     * @param int $hipayId
     */
    public function __construct($miraklId, $hipayId, $paymentVoucher, $amount, $originAmount, $balance)
    {
        $this->miraklId = $miraklId;
        $this->hipayId = $hipayId;
        $this->paymentVoucher = $paymentVoucher;
        $this->amount = $amount;
        $this->originAmount = $originAmount;
        $this->balance = $balance;
        $this->dateCreated = new DateTime();
    }

    /**
     * @return int
     */
    public function getMiraklId()
    {
        return $this->miraklId;
    }

    /**
     * @param int $miraklId
     */
    public function setMiraklId($miraklId)
    {
        $this->miraklId = $miraklId;
    }

    /**
     * @return int
     */
    public function getHipayId()
    {
        return $this->hipayId;
    }

    /**
     * @param int $hipayId
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
     * @return int
     */
    public function getStatusTransferts()
    {
        return $this->statusTransferts;
    }

    /**
     * @param int $status
     */
    public function setStatusTransferts($statusTransferts)
    {
        $this->statusTransferts = $statusTransferts;
    }

    /**
     * @return int
     */
    public function getStatusWithDrawal()
    {
        return $this->statusWithDrawal;
    }

    /**
     * @param int $status
     */
    public function setStatusWithDrawal($statusWithDrawal)
    {
        $this->statusWithDrawal = $statusWithDrawal;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     *
     * @param type $paymentVoucher
     */
    function setPaymentVoucher($paymentVoucher)
    {
        $this->paymentVoucher = $paymentVoucher;
    }

    /**
     *
     * @param DateTime $dateCreated
     */
    function setDateCreated(DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
    
    /**
     *
     * @return type
     */
    function getPaymentVoucher()
    {
        return $this->paymentVoucher;
    }

    /**
     *
     * @return type
     */
    function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     *
     * @return type
     */
    function getAmountOrigin()
    {
        return $this->amountOrigin;
    }

    /**
     *
     * @param type $amountOrigin
     */
    function setAmountOrigin($amountOrigin)
    {
        $this->amountOrigin = $amountOrigin;
    }



}