<?php
namespace HiPay\Wallet\Mirakl\Integration\Command\Vendor;

use DateTime;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HiPay\Wallet\Mirakl\Vendor\Processor as VendorProcessor;

/**
 * File ProcessCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class ProcessCommand extends AbstractCommand
{
    const LAST_UPDATE = 'lastUpdate';
    const TMP_PATH = 'tmpPath';

    const DEFAULT_TMP_PATH = '/tmp';

    /** @var  VendorProcessor */
    protected $processor;

    /** @var  string to the tmp */
    protected $tmpPath;

    public function __construct(
        LoggerInterface $logger,
        VendorProcessor $processor,
        $tmpPath
    )
    {
        $this->processor = $processor;
        $this->tmpPath = $tmpPath;
        parent::__construct($logger);
    }

    protected function configure()
    {
        $this->setName('vendor:process')
            ->setDescription('Update the vendors data')
            ->addArgument(self::LAST_UPDATE, InputArgument::OPTIONAL, 'The last time the database was updated (Format : YYYY-mm-dd)')
            ->addOption(self::TMP_PATH, 'z',InputOption::VALUE_REQUIRED, 'The path where to save the tmp file', $this->tmpPath);
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputedDate =  $input->getArgument(self::LAST_UPDATE);
        $lastUpdate = $inputedDate ? new DateTime($inputedDate) : null;
        $this->logger->debug("Inputed last update {date}", array('date' => $lastUpdate));

        $tmpPath = $input->getOption(self::TMP_PATH) ?: static::DEFAULT_TMP_PATH;

        $this->logger->debug(
            "Arguments \n lastUpdate : $inputedDate \n tmpPath : $tmpPath ",
            array('tmpPath' => $tmpPath, 'lastUpdated' => $lastUpdate)
        );

        try {
            $this->processor->process($tmpPath, $lastUpdate);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getTmpPath()
    {
        return $this->tmpPath;
    }

    /**
     * @param string $tmpPath
     */
    public function setTmpPath($tmpPath)
    {
        $this->tmpPath = $tmpPath;
    }

}