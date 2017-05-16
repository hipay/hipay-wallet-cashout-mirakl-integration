<?php
namespace HiPay\Wallet\Mirakl\Integration\Command\Cashout;

use Exception;
use HiPay\Wallet\Mirakl\Cashout\Processor as CashoutProcessor;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * File ProcessCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class ProcessCommand extends AbstractCommand
{
    /** @var  CashoutProcessor */
    protected $processor;

    public function __construct(
        LoggerInterface $logger,
        CashoutProcessor $processor
    )
    {
        parent::__construct($logger);
        $this->processor = $processor;

    }

    protected function configure()
    {
        $this->setName('cashout:process')
            ->setDescription('Process the cashout operations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->processor->process();
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}