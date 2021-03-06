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

use Doctrine\ORM\EntityRepository;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsManagerInterface;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;
use Doctrine\DBAL\Types\Type;

/**
 * Class LogVendorsRepository
 *
 */
class LogVendorsRepository extends AbstractTableRepository implements LogVendorsManagerInterface
{

    /**
     * @param $miraklId
     * @param $hipayId
     * @param $login
     * @param $statusWalletAccount
     * @param $status
     * @param $message
     * @param $nbDoc
     * @param $country
     *
     * @return LogVendors
     */
    public function create(
        $miraklId,
        $hipayId,
        $login,
        $statusWalletAccount,
        $status,
        $message,
        $nbDoc,
        $country,
        $paymentBlocked
    ) {
        $logVendor = new LogVendors(
            $miraklId,
            $hipayId,
            $login,
            $statusWalletAccount,
            $status,
            $message,
            $nbDoc,
            $country,
            $paymentBlocked
        );

        return $logVendor;
    }

    /**
     * @param array $logVendors
     * @return mixed
     */
    public function saveAll(array $logVendors)
    {
        foreach ($logVendors as $logVendor) {
            $this->_em->persist($logVendor);
        }

        $this->_em->flush();
    }

    /**
     * Insert more data if you want
     *
     * @param LogVendorsInterface $vendor
     * @param array $logData
     */
    public function update(LogVendorsInterface $vendor, array $logData) {
        return;
    }

    /**
     * @param $shopId
     * @return LogVendorsInterface|null if not found
     */
    public function findByMiraklId($shopId)
    {
        return $this->findOneBy(array('miraklId' => $shopId));
    }

    /**
     * @param $shopId
     * @return LogVendorsInterface|null if not found
     */
    public function findByHipayId($shopId)
    {
        return $this->findOneBy(array('hipayId' => $shopId));
    }

    /**
     * @param LogVendorsInterface $logVendors
     * @return mixed
     */
    public function save($logVendors)
    {
        $this->_em->persist($logVendors);
        $this->_em->flush();
    }

    /**
     * @param LogVendorsInterface $logVendors
     * @return bool
     */
    public function isValid(LogVendorsInterface $logVendors)
    {
        return true;
    }

    /**
     * @return array
     */
    public function getAllCountries()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a.country')
            ->from($this->_entityName, 'a')
            ->groupBy('a.country');

        return $queryBuilder->getQuery()->getResult();
    }

    protected function getSelectString()
    {
        return 'a.miraklId, a.login, a.hipayId, a.status, a.statusWalletAccount,'.
        ' a.message, a.enabled, a.nbDoc, a.date, a.country, a.paymentBlocked';
    }

    protected function getCountString()
    {
        return 'COUNT(a.miraklId)';
    }

    protected function prepareAjaxRequest($queryBuilder, $search, $custom)
    {
        if (!empty($search)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('a.miraklId', '?1'),
                    $queryBuilder->expr()->like('a.login', '?1'),
                    $queryBuilder->expr()->like(
                        'a.hipayId',
                        '?1'
                    )
                )
            )
                ->setParameter(1, '%' . $search . '%');
        }

        if (isset($custom["country"]) && !empty($custom["country"])) {
            if (!in_array("", $custom["country"])) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->in('a.country', $custom["country"])
                );
            } else {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->in('a.country', $custom["country"]),
                        $queryBuilder->expr()->isNull('a.country')
                    )
                );
            }
        }

        if (isset($custom["status"]) && $custom["status"] != -1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('a.status', $custom["status"])
            );
        }
        if (isset($custom["wallet-status"]) && $custom["wallet-status"] != -1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('a.statusWalletAccount', $custom["wallet-status"])
            );
        }

        if (isset($custom["date-start"]) && !empty($custom["date-start"])) {
            $dateStart = \DateTime::createFromFormat('d/m/Y H:i:s', $custom["date-start"] . ' 00:00:00');
            if ($dateStart) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->gte('a.date', ':start')
                )
                    ->setParameter('start', $dateStart, Type::DATETIME);
            }
        }

        if (isset($custom["date-end"]) && !empty($custom["date-end"])) {
            $dateStart = \DateTime::createFromFormat('d/m/Y H:i:s', $custom["date-end"] . ' 23:59:59');
            if ($dateStart) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->lte('a.date', ':last')
                )
                    ->setParameter('last', $dateStart, Type::DATETIME);
            }
        }

        return $queryBuilder;
    }
}
