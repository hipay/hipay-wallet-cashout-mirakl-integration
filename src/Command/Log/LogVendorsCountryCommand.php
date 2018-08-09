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
 * Class LogVendorsCountryCommand
 * @package HiPay\Wallet\Mirakl\Integration\Command\Log
 */
class LogVendorsCountryCommand extends AbstractCommand
{
    protected $batchManager;
    protected $vendorManager;
    protected $logVendorsManager;
    protected $processor;

    public function __construct(
        LoggerInterface $logger,
        VendorProcessor $processor,
        BatchRepository $batchManager,
        VendorRepository $vendorManager,
        LogVendorsRepository $logVendorsManager
    ) {
        $this->batchManager = $batchManager;
        $this->vendorManager = $vendorManager;
        $this->logVendorsManager = $logVendorsManager;
        $this->processor = $processor;

        parent::__construct($logger);
    }

    protected function configure()
    {
        $this->setName('logs:vendors:country')
            ->setDescription('Recover country for vendors from Mirakl');
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
                $log = $this->logVendorsManager->findByMiraklId($vendor->getMiraklId());
                if ($log && ($log->getCountry() === null || $vendor->getCountry() === null)) {
                    $miraklData = $this->processor->getVendorByShopId($vendor->getMiraklId());

                    $log->setCountry($miraklData["contact_informations"]["country"]);
                    $vendor->setCountry($miraklData["contact_informations"]["country"]);
                    $logs[] = $log;

                    $this->logger->info(
                        "Setting Country (".$miraklData["contact_informations"]["country"].")".
                        " for vendor " . $vendor->getMiraklId() . " (Mirakl Id)"
                    );

                } else {
                    $this->logger->info("Country already set for vendor " . $vendor->getMiraklId() . " (Mirakl Id)");
                }
            } catch (\Exception $e) {
                $this->logger->warning(
                    $e->getMessage(),
                    array('miraklId' => $vendor->getMiraklId(), "action" => "Logs vendors recover")
                );
            }
        }

        $this->logVendorsManager->saveAll($logs);
        $this->vendorManager->saveAll($vendors);
    }
}
