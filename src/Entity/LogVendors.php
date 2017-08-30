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
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LogVendors
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository")
 * @ORM\Table(name="log_vendors")
 */
class LogVendors implements LogVendorsInterface
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
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $miraklId;

    /**
     * @var int The HiPay Wallet account ID
     *
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $hipayId;

    /**
     * @var string The HiPay Wallet account login
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $login;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $statusWalletAccount;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $message;

    /**
     * @var int
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $nbDoc;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     *
     */
    protected $date;

    /**
     * Vendor constructor.
     * @param int $miraklId
     * @param int $hipayId
     */
    public function __construct($miraklId, $hipayId, $login, $statusWalletAccount, $status, $message, $nbDoc)
    {
        $this->miraklId = $miraklId;
        $this->hipayId = $hipayId;
        $this->login = $login;
        $this->statusWalletAccount = $statusWalletAccount;
        $this->status = $status;
        $this->message = $message;
        $this->nbDoc = $nbDoc;
        $this->date = new \DateTime();
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function getNbDoc()
    {
        return $this->nbDoc;
    }

    /**
     * @param string $nbDoc
     */
    public function setNbDoc($nbDoc)
    {
        $this->nbDoc = $nbDoc;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param String $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}