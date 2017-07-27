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
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $miraklId;

    /**
     * @var int The HiPay Wallet account ID
     *
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $hipayId;

    /**
     * @var float
     *
     * @ORM\Column(type="float", unique=false, nullable=false)
     */
    protected $amount;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $statusTransferts;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $statusWithDrawal;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
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
    public function __construct($miraklId, $hipayId)
    {
        $this->miraklId = $miraklId;
        $this->hipayId = $hipayId;
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
}