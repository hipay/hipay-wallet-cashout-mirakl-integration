<?php
namespace HiPay\Wallet\Mirakl\Integration\Entity;

use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\ManagerInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\OperationInterface;
use HiPay\Wallet\Mirakl\Cashout\Model\Operation\Status;
use HiPay\Wallet\Mirakl\Vendor\Model\DocumentInterface;
use HiPay\Wallet\Mirakl\Vendor\Model\DocumentManagerInterface;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorInterface;
use Mustache_Engine;

class DocumentRepository extends EntityRepository implements DocumentManagerInterface
{
    /**
     * @param array $documents
     */
    public function saveAll(array $documents)
    {
        foreach ($documents as $document) {
            $this->_em->persist($document);
        }

        $this->_em->flush();
    }

    /**
     * @param DocumentInterface $document
     */
    public function save(DocumentInterface $document)
    {
        $this->_em->persist($document);
        $this->_em->flush();
    }

    /**
     * @param VendorInterface $vendor
     * @return array
     */
    public function findByVendor(VendorInterface $vendor)
    {
        return $this->findBy(array("vendor" => $vendor));
    }

    /**
     * @param $miraklDocumentId
     * @param \DateTime $miraklUploadDate
     * @param $documentType
     * @param VendorInterface $vendor
     * @return DocumentInterface
     */
    public function create(
        $miraklDocumentId,
        \DateTime $miraklUploadDate,
        $documentType,
        VendorInterface $vendor
    )
    {
        $document = new Document($miraklDocumentId, $miraklUploadDate, $documentType, $vendor);
        return $document;
    }

}