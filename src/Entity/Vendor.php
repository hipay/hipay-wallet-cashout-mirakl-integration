<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use DateTime;
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
     * @ORM\Column(type="integer", unique=true, nullable=false)
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
     * @var \DateTime $lastModification
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $lastModification;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $hipayId;

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
     * @return DateTime
     */
    public function getLastModification()
    {
        return $this->lastModification;
    }

    /**
     * @param DateTime $lastModification
     */
    public function setLastModification($lastModification)
    {
        $this->lastModification = $lastModification;
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
}