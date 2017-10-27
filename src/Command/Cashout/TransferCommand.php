<?php

namespace HiPay\Wallet\Mirakl\Integration\Command\Cashout;

use Exception;
use HiPay\Wallet\Mirakl\Cashout\Transfer as TransferProcessor;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HiPay\Wallet\Mirakl\Integration\Entity\Batch;
use HiPay\Wallet\Mirakl\Integration\Entity\BatchRepository;


class TransferCommand extends AbstractCommand
{

    protected $transferProcessor;

    protected $batchManager;

    public function __construct(LoggerInterface $logger, BatchRepository $batchManager, TransferProcessor $processor)
    {
        parent::__construct($logger);
        $this->processor    = $processor;
        $this->batchManager = $batchManager;
    }

    protected function configure()
    {
        $this->setName('cashout:transfer')
             ->setDescription('Process the cashout transfer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batch = new Batch($this->getName());
        $this->batchManager->save($batch);

        try {
            $this->processor->process();
            
            $batch->setEndedAt(new \DateTime());
            $this->batchManager->save($batch);
        } catch (Exception $e) {

            $batch->setError($e->getMessage());
            $this->batchManager->save($batch);

            $this->logger->critical($e->getMessage());
        }
    }
}