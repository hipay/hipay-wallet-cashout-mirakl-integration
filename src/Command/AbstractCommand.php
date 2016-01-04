<?php
namespace Hipay\SilexIntegration\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;

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

    /**
     * AbstractCommand constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

}