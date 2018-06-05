<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Gedmo\Timestampable\Timestampable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vendor
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\VendorRepository")
 * @ORM\Table(name="vendors")
 */
class Vendor implements VendorInterface, Timestampable
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Email
     */
    protected $email;

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
     * @var int The HiPay account user space ID
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $hipayUserSpaceId;

    /**
     * @var int Whether the HiPay Wallet account is identified
     *
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotNull
     */
    protected $hipayIdentified;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vatNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $callbackSalt;

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
     * @param string $email
     * @param int $hipayId
     */
    public function __construct($email, $miraklId, $hipayId)
    {
        $this->miraklId = $miraklId;
        $this->email = $email;
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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function getHipayUserSpaceId()
    {
        return $this->hipayUserSpaceId;
    }

    /**
     * @param int $hipayUserSpaceId
     */
    public function setHipayUserSpaceId($hipayUserSpaceId)
    {
        $this->hipayUserSpaceId = $hipayUserSpaceId;
    }

    /**
     * @return int
     */
    public function getHipayIdentified()
    {
        return $this->hipayIdentified;
    }

    /**
     * @param int $hipayIdentified
     */
    public function setHipayIdentified($hipayIdentified)
    {
        $this->hipayIdentified = $hipayIdentified;
    }

    /**
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return string
     */
    public function getCallbackSalt()
    {
        return $this->callbackSalt;
    }

    /**
     * @param string $callbackSalt
     */
    public function setCallbackSalt($callbackSalt)
    {
        $this->callbackSalt = $callbackSalt;
    }

    /**
     * @return Vendor
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param Vendor $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
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
