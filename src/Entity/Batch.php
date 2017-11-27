<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\Entity;

use Gedmo\Timestampable\Timestampable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Batch
 *
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\BatchRepository")
 * @ORM\Table(name="batchs")
 * @ORM\HasLifecycleCallbacks
 */
class Batch
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
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="error", type="text", nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(name="started_at", type="datetime")
     */
    private $startedAt;

    /**
     * @ORM\Column(name="ended_at", type="datetime", nullable=true)
     */
    private $endedAt;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStartedAt()
    {
        return $this->startedAt;
    }

    public function getEndedAt()
    {
        return $this->endedAt;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->startedAt = new \DateTime();
        $this->error = null;
    }
}