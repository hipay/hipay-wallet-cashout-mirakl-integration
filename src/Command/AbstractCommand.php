<?php
namespace HiPay\Wallet\Mirakl\Integration\Command;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HiPay\Wallet\Mirakl\Integration\Console\Logger as ConsoleLogger;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
    }

    public function addDebugLogger(Logger $logger, SymfonyStyle $output) {
        $lineFormatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\n");
        $lineFormatter->allowInlineLineBreaks(true);
        $lineFormatter->ignoreEmptyContextAndExtra(true);

        $stdoutHandler = new PsrHandler(new ConsoleLogger($output));
        $stdoutHandler->setFormatter($lineFormatter);

        $logger->pushHandler($stdoutHandler);
    }
}