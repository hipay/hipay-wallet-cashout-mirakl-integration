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

use Gedmo\Timestampable\Timestampable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use HiPay\Wallet\Mirakl\Notification\Model\LogGeneralInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LogOperations
 *
 * @ORM\Entity(repositoryClass="HiPay\Wallet\Mirakl\Integration\Entity\LogGeneralRepository")
 * @ORM\Table(name="log_general")
 * @ORM\HasLifecycleCallbacks
 */
class LogGeneral
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
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    protected $miraklId;

    /**
     * @ORM\Column(name="action", type="text", nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\Column(name="context", type="array")
     */
    private $context;

    /**
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;

    /**
     * @ORM\Column(name="level_name", type="string", length=50)
     */
    private $levelName;

    /**
     * @ORM\Column(name="extra", type="array")
     */
    private $extra;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct($miraklId = null, $action = null, $message = null, $context = null, $level = null,
                                $levelName = null, $extra = null)
    {
        $this->miraklId  = $miraklId;
        $this->action    = $action;
        $this->message   = $message;
        $this->context   = $context;
        $this->level     = $level;
        $this->levelName = $levelName;
        $this->extra     = $extra;
    }

    public function getMiraklId()
    {
        return $this->miraklId;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getLevelName()
    {
        return $this->levelName;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setMiraklId($miraklId)
    {
        $this->miraklId = $miraklId;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function setLevelName($levelName)
    {
        $this->levelName = $levelName;
    }

    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
    }
}