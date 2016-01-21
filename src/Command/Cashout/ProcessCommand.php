<?php
namespace Hipay\SilexIntegration\Command\Cashout;
use Exception;
use Hipay\MiraklConnector\Cashout\Processor as CashoutProcessor;
use Hipay\SilexIntegration\Command\AbstractCommand;
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

    /** @var  string */
    protected $publicLabelTemplate;

    /** @var  string */
    protected $privateLabelTemplate;

    /** @var  string */
    protected $withdrawLabelTemplate;

    public function __construct(
        LoggerInterface $logger,
        CashoutProcessor $processor,
        $publicLabelTemplate,
        $privateLabelTemplate,
        $withdrawLabelTemplate
    )
    {
        parent::__construct($logger);
        $this->processor = $processor;
        $this->publicLabelTemplate = $publicLabelTemplate;
        $this->privateLabelTemplate = $privateLabelTemplate;
        $this->withdrawLabelTemplate = $withdrawLabelTemplate;

    }

    protected function configure()
    {
        $this->setName('cashout:process')
            ->setDescription('Process the cashout operations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->processor->process(
                $this->publicLabelTemplate,
                $this->privateLabelTemplate,
                $this->withdrawLabelTemplate
            );
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}