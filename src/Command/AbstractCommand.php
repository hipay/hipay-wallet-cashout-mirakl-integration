<?php
namespace Hipay\SilexIntegration\Command;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * File AbstractCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
abstract class AbstractCommand extends Command
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var LoggerInterface */
    protected $logger;

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * AbstractCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ValidatorInterface $validator
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->validator = $validator;
    }

}