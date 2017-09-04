<?php

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

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getStartedAt()
    {
        return $this->startedAt;
    }

    function getEndedAt()
    {
        return $this->endedAt;
    }

    function getError()
    {
        return $this->error;
    }

    function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
    }

    function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->startedAt = new \DateTime();
        $this->error     = null;
    }
}