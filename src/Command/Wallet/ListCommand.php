<?php
namespace HiPay\Wallet\Mirakl\Integration\Command\Wallet;

use HiPay\Wallet\Mirakl\Vendor\Processor;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * File ListCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class ListCommand extends AbstractCommand
{
    const PAST_DATE = 'pastDate';

    /** @var Processor  */
    protected $vendorProcessor;
    /**
     * @var int
     */
    protected $merchantGroupId;

    public function __construct(LoggerInterface $logger, Processor $processor, $merchantGroupId)
    {
        parent::__construct($logger);
        $this->vendorProcessor = $processor;
        $this->merchantGroupId = $merchantGroupId;
    }

    public function configure()
    {
        parent::configure();
        $this->setName('vendor:wallet:list')
            ->setDescription('List the wallets created at HiPay')
            ->addOption(
            self::PAST_DATE,
            'date',
            InputOption::VALUE_REQUIRED,
            "Limit to the wallet created after given date"
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $io = new SymfonyStyle($input, $output);
        $pastDate = $input->getOption(self::PAST_DATE) ? new \DateTime($input->getOption(self::PAST_DATE)) : null;
        $data = $this->vendorProcessor->getWallets($this->merchantGroupId, $pastDate);
        $io->title("Hipay Wallets");
        $io->table(array_keys(reset($data)), $data);
    }
}