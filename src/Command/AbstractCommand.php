<?php
namespace Hipay\SilexIntegration\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * File AbstractCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
abstract class AbstractCommand extends Command
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * AbstractCommand constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

}