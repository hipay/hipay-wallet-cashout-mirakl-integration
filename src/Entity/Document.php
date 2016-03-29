<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use DateTime;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\OperationInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\Status;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use HiPay\Wallet\Mirakl\Vendor\Model\DocumentInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Document
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\DocumentRepository")
 * @ORM\Table(name="documents")
 *
 */
class Document implements DocumentInterface
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
    protected $miraklDocumentId;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $miraklUploadDate;

    /**
     * @var string
     * @ORM\Column(type="string", unique=false, nullable=false)
     */
    protected $documentType;

    /**
     * @ORM\ManyToOne(targetEntity="Vendor")
     */
    protected $vendor;

    /**
     * Document constructor.
     * @param int $id
     * @param int $miraklDocumentId
     * @param DateTime $miraklUploadDate
     * @param int $documentType
     * @param $vendor
     */
    public function __construct($miraklDocumentId, DateTime $miraklUploadDate, $documentType, $vendor)
    {
        $this->miraklDocumentId = $miraklDocumentId;
        $this->miraklUploadDate = $miraklUploadDate;
        $this->documentType = $documentType;
        $this->vendor = $vendor;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMiraklDocumentId()
    {
        return $this->miraklDocumentId;
    }

    /**
     * @param int $miraklDocumentId
     */
    public function setMiraklDocumentId($miraklDocumentId)
    {
        $this->miraklDocumentId = $miraklDocumentId;
    }

    /**
     * @return DateTime
     */
    public function getMiraklUploadDate()
    {
        return $this->miraklUploadDate;
    }

    /**
     * @param DateTime $miraklUploadDate
     */
    public function setMiraklUploadDate($miraklUploadDate)
    {
        $this->miraklUploadDate = $miraklUploadDate;
    }

    /**
     * @return int
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * @param int $documentType
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }


}