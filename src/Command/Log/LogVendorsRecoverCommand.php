<?php

namespace HiPay\Wallet\Mirakl\Integration\Command\Log;

use DateTime;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HiPay\Wallet\Mirakl\Integration\Entity\Batch;
use HiPay\Wallet\Mirakl\Integration\Entity\LogVendors;
use HiPay\Wallet\Mirakl\Integration\Entity\BatchRepository;
use HiPay\Wallet\Mirakl\Integration\Entity\VendorRepository;
use HiPay\Wallet\Mirakl\Integration\Entity\LogVendorsRepository;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;
use HiPay\Wallet\Mirakl\Notification\Model\LogVendorsInterface;

/**
 * File LogVendorsRecoverCommand.php
 *
 */
class LogVendorsRecoverCommand extends AbstractCommand
{
    protected $batchManager;
    protected $vendorManager;
    protected $logVendorsManager;
    protected $processor;

    public function __construct(LoggerInterface $logger, VendorProcessor $processor, BatchRepository $batchManager,
                                VendorRepository $vendorManager, LogVendorsRepository $logVendorsManager)
    {
        $this->batchManager      = $batchManager;
        $this->vendorManager     = $vendorManager;
        $this->logVendorsManager = $logVendorsManager;
        $this->processor         = $processor;

        parent::__construct($logger);
    }

    protected function configure()
    {
        $this->setName('logs:vendors:recover')
            ->setDescription('Recover Logs vendors from vendors');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $batch = new Batch($this->getName());
        $this->batchManager->save($batch);

        try {
            $this->processVendors();
            $batch->setEndedAt(new \DateTime());
            $this->batchManager->save($batch);
        } catch (\Exception $e) {

            $batch->setError($e->getMessage());
            $this->batchManager->save($batch);

            $this->logger->critical($e->getMessage());
        }
    }

    private function processVendors()
    {
        $vendors = $this->vendorManager->findAll();

        $logs = array();

        foreach ($vendors as $vendor) {
            try {
                if (!$this->logVendorsManager->findByMiraklId($vendor->getMiraklId())) {

                    $login = $this->processor->getLogin($vendor->getMiraklId());

                    $log = new LogVendors();
                    $log->setMiraklId($vendor->getMiraklId());
                    $log->setHipayId($vendor->getHipayId());
                    $log->setLogin($login);
                    $log->setStatus(LogVendorsInterface::SUCCESS);
                    if ($vendor->getHipayIdentified()) {
                        $log->setStatusWalletAccount(LogVendorsInterface::WALLET_IDENTIFIED);
                    } else {
                        $log->setStatusWalletAccount(LogVendorsInterface::WALLET_NOT_IDENTIFIED);
                    }
                    $log->setDate(DateTime::createFromFormat('Y-m-d', '2017-09-04'));  // set default date to dashboard release
                    $logs[] = $log;

                    $this->logger->info("create log from vendor ".$vendor->getMiraklId()." (Mirakl Id)");
                }
            } catch (Exception $e) {
                $this->logger->warning($e->getMessage(), array('miraklId' => $vendor->getMiraklId(), "action" => "Logs vendors recover"));
            }
        }

        $this->logVendorsManager->saveAll($logs);
    }
}