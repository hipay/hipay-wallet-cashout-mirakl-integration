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
    const ZIP_PATH = 'zipPath';
    const FTP_PATH = 'ftpPath';

    const DEFAULT_ZIP_PATH = '/tmp/documents.zip';
    const DEFAULT_FTP_PATH = '/';

    /** @var  VendorProcessor */
    protected $processor;

    /** @var  string to the zip */
    protected $zipPath;

    /** @var  string the path on the ftp */
    protected $ftpPath;

    public function __construct(
        LoggerInterface $logger,
        VendorProcessor $processor,
        $zipPath,
        $ftpPath
    )
    {
        $this->processor = $processor;
        $this->zipPath = $zipPath;
        $this->ftpPath = $ftpPath;
        parent::__construct($logger);
    }

    protected function configure()
    {
        $this->setName('vendor:process')
            ->setDescription('Update the vendors data')
            ->addArgument(self::LAST_UPDATE, InputArgument::OPTIONAL, 'The last time the database was updated (Format : YYYY-mm-dd)')
            ->addOption(self::ZIP_PATH, 'z',InputOption::VALUE_REQUIRED, 'The path where to save the zip file', $this->zipPath)
            ->addOption(self::FTP_PATH, 'f', InputOption::VALUE_REQUIRED, 'The path on the ftp where to save the documents', $this->ftpPath);
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputedDate =  $input->getArgument(self::LAST_UPDATE);
        $lastUpdate = $inputedDate ? new DateTime($inputedDate) : null;
        $this->logger->debug("Inputed last update {date}", array('date' => $lastUpdate));

        $zipPath = $input->getOption(self::ZIP_PATH) ?: static::DEFAULT_ZIP_PATH;

        $ftpPath = $input->getOption(self::FTP_PATH) ?: static::DEFAULT_FTP_PATH;
        $this->logger->debug(
            "Arguments \n lastUpdate : $inputedDate \n zipPath : $zipPath \n ftpPath : $ftpPath ",
            array('zipPath' => $zipPath, 'lastUpdated' => $lastUpdate, 'ftpPath' => $ftpPath)
        );

        try {
            $this->processor->process($zipPath, $ftpPath, $lastUpdate);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param string $zipPath
     * @return ProcessCommand
     */
    public function setZipPath($zipPath)
    {
        $this->zipPath = $zipPath;
        return $this;
    }

    /**
     * @param string $ftpPath
     * @return ProcessCommand
     */
    public function setFtpPath($ftpPath)
    {
        $this->ftpPath = $ftpPath;
        return $this;
    }

}