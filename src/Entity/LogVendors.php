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
     * @var Vendor enabled or not
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $enabled;

    /**
     * @var Vendor country
     *
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $country;

    /**
     * @var Vendor payment blocked
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    protected $paymentBlocked;

    /**
     * Vendor constructor.
     * @param int $miraklId
     * @param int $hipayId
     */
    public function __construct(
        $miraklId = null,
        $hipayId = null,
        $login = null,
        $statusWalletAccount = null,
        $status = null,
        $message = null,
        $nbDoc = 0,
        $country = null,
        $paymentBlocked = false
    ) {
        $this->miraklId = $miraklId;
        $this->hipayId = $hipayId;
        $this->login = $login;
        $this->statusWalletAccount = $statusWalletAccount;
        $this->status = $status;
        $this->message = $message;
        $this->nbDoc = $nbDoc;
        $this->date = new \DateTime();
        $this->enabled = true;
        $this->country = $country;
        $this->paymentBlocked = $paymentBlocked;
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

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getStatusWalletAccount()
    {
        return $this->statusWalletAccount;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setStatusWalletAccount($statusWalletAccount)
    {
        $this->statusWalletAccount = $statusWalletAccount;
    }

    /**
     * @return Boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param Boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return Vendor
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Vendor $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Boolean
     */
    public function isPaymentBlocked()
    {
        return $this->paymentBlocked;
    }

    /**
     * @param Boolean $paymentBlocked
     */
    public function setPaymentBlocked($paymentBlocked)
    {
        $this->paymentBlocked = $paymentBlocked;
    }
}
