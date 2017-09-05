<?php

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorManagerInterface;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorInterface;

/**
 * Class VendorRepository
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class VendorRepository extends AbstractTableRepository implements VendorManagerInterface
{

    /**
     * @param $email
     * @param $miraklId
     * @param $hipayId
     * @param array $miraklData
     *
     * @return VendorInterface
     */
    public function create(
    $email, $miraklId, $hipayId, $hipayUserSpaceId, $hipayIdentified, $vatNumber, $callbackSalt,
    array $miraklData = array()
    )
    {
        if ($vatNumber == null && array_key_exists('pro_details', $miraklData)) {
            $vatNumber = $miraklData['pro_details']['VAT_number'];
        }

        $vendor = new Vendor($email, $miraklId, $hipayId, $hipayUserSpaceId, $hipayIdentified, $vatNumber);
        return $vendor;
    }

    /**
     * @param array $vendors
     * @return mixed
     */
    public function saveAll(array $vendors)
    {
        foreach ($vendors as $vendor) {
            $this->_em->persist($vendor);
        }

        $this->_em->flush();
    }

    /**
     * Insert more data if you want
     *
     * @param VendorInterface $vendor
     * @param array $miraklData
     *
     * @return void
     */
    public function update(
    VendorInterface $vendor, array $miraklData
    )
    {
        return;
    }

    /**
     * @param $shopId
     * @return VendorInterface|null if not found
     */
    public function findByMiraklId($shopId)
    {
        return $this->findOneBy(array('miraklId' => $shopId));
    }

    /**
     * @param $shopId
     * @return VendorInterface|null if not found
     */
    public function findByHipayId($shopId)
    {
        return $this->findOneBy(array('hipayId' => $shopId));
    }

    /**
     * @param string $email
     * @return VendorInterface|null if not found
     */
    public function findByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }

    /**
     * @param VendorInterface $vendor
     * @return mixed
     */
    public function save($vendor)
    {
        $this->_em->persist($vendor);
        $this->_em->flush();
    }

    /**
     * @param $vendor
     * @return boolean
     */
    public function isValid(VendorInterface $vendor)
    {
        return true;
    }

    protected function getSelectString(){
        return 'a.miraklId, a.hipayId, a.hipayIdentified';
    }

    protected function getCountString(){
        return 'COUNT(a.miraklId)';
    }

    protected function prepareAjaxRequest($queryBuilder, $search, $custom)
    {

        if (!empty($search)) {
            $queryBuilder->where(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('a.miraklId', '?1'),
                        $queryBuilder->expr()->like('a.hipayId','?1')
                    )
                )
                ->setParameter(1, '%'.$search.'%');
        }

        return $queryBuilder;
    }

}