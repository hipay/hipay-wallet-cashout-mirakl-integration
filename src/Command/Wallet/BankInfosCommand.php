<?php
namespace HiPay\Wallet\Mirakl\Integration\Command\Wallet;

use HiPay\Wallet\Mirakl\Api\HiPay\Model\Status\BankInfo;
use HiPay\Wallet\Mirakl\Vendor\Model\ManagerInterface;
use HiPay\Wallet\Mirakl\Vendor\Model\VendorInterface;
use HiPay\Wallet\Mirakl\Vendor\Processor;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * File BankInfosCommand.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class BankInfosCommand extends AbstractCommand
{
    const HIPAY_ID = 'hipayId';

    /** @var Processor  */
    protected $vendorProcessor;

    /** @var  ManagerInterface */
    protected $vendorManager;
    /**
     * @var VendorInterface
     */
    protected $technical;
    /**
     * @var VendorInterface
     */
    protected $operator;

    public function __construct(
        LoggerInterface $logger,
        Processor $processor,
        ManagerInterface $manager,
        VendorInterface $operator,
        VendorInterface $technical
    )
    {
        parent::__construct($logger);
        $this->vendorProcessor = $processor;
        $this->vendorManager = $manager;
        $this->technical = $technical;
        $this->operator = $operator;
    }

    public function configure()
    {
        parent::configure();
        $this->setName('vendor:wallet:bankInfos')
             ->setDescription('Fetch the wallet bank info from HiPay')
             ->addArgument(static::HIPAY_ID, InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Banking information");

        $hipayID = $input->getArgument(self::HIPAY_ID);

        $vendor = $this->getVendor($hipayID);

        $status = $this->vendorProcessor->getBankInfoStatus($vendor);
        $io->writeln($status);

        if ($status == BankInfo::VALIDATED) {
            $bankData = $this->vendorProcessor->getBankInfo($vendor)->getData();

            foreach ($bankData as $key => $value) {
                $io->writeln("$key \t $value");
            }
        }
    }

    protected function getVendor($hipayId)
    {
        if ($hipayId == $this->operator->getHipayId()) {
            return $this->operator;
        }

        if ($hipayId == $this->technical->getHipayId()) {
            return $this->technical;
        }

        return $this->vendorManager->findByHipayId($hipayId);
    }
}