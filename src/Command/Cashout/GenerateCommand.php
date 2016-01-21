<?php
namespace Hipay\SilexIntegration\Command\Cashout;
use DateInterval;
use \DateTime;
use Exception;
use Hipay\MiraklConnector\Cashout\Initializer as CashoutInitializer;
use Hipay\SilexIntegration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * File ProcessCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class GenerateCommand extends AbstractCommand
{
    const CRON_DATE = 'executionDate';
    const CYCLE_DATE = 'cycleDate';
    const INTERVAL_BEFORE = 'beforeInterval';
    const INTERVAL_AFTER = 'afterInterval';

    /** @var  CashoutInitializer */
    protected $processor;

    /** @var  array  */
    protected $cycleDays;
    /** @var  int */
    protected $cycleHour;
    /** @var  int */
    protected $cycleMinute;
    /** @var */
    protected $cycleIntervalBefore;
    /** @var */
    protected $cycleIntervalAfter;

    /**
     * GenerateCommand constructor.
     * @param LoggerInterface $logger
     * @param CashoutInitializer $processor
     * @param array $cycleDays
     * @param int $cycleHour
     * @param int $cycleMinute
     * @param $cycleIntervalBefore
     * @param $cycleIntervalAfter
     */
    public function __construct(
        LoggerInterface $logger,
        CashoutInitializer $processor,
        array $cycleDays,
        $cycleHour,
        $cycleMinute,
        $cycleIntervalBefore,
        $cycleIntervalAfter
    )
    {

        $this->processor = $processor;
        $this->cycleDays = $cycleDays;
        $this->cycleHour = $cycleHour;
        $this->cycleMinute = $cycleMinute;
        $this->cycleIntervalBefore = $cycleIntervalBefore;
        $this->cycleIntervalAfter = $cycleIntervalAfter;
        parent::__construct($logger);
    }


    protected function configure()
    {
        $this->setName('cashout:generate')
            ->setDescription('Generate the cashout operations')
            ->addOption(
                self::CRON_DATE,
                'e',
                InputOption::VALUE_REQUIRED,
                "A specific date consider to generate the cycle date.". PHP_EOL . "Format : YYYY-mm-dd",
                date('Y-m-d')
            )
            ->addArgument(
                self::CYCLE_DATE,
                InputArgument::OPTIONAL,
                "Directly set the cycle date." . PHP_EOL . "Format : YYYY-mm-dd"
            )
            ->addOption(
                self::INTERVAL_BEFORE,
                'b',
                InputOption::VALUE_REQUIRED,
                'Time to retire for the interval',
                $this->cycleIntervalBefore
            )
            ->addOption(
                self::INTERVAL_AFTER,
                'a',
                InputOption::VALUE_REQUIRED,
                'Time to add for the interval',
                $this->cycleIntervalAfter
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cronDate = new DateTime($input->getOption(self::CRON_DATE));
        $cronDate->setTime($this->cycleHour, $this->cycleMinute);

        $cycleDate = $input->getArgument(self::CYCLE_DATE);
        $cycleDate = $cycleDate ? new DateTime($cycleDate) : $this->getCycleDate($this->cycleDays, $cronDate);

        $cycleStartDate = clone $cycleDate;
        $cycleStartDate->sub(DateInterval::createFromDateString($input->getOption(self::INTERVAL_BEFORE)));
        $cycleEndDate = clone $cycleDate;
        $cycleEndDate->add(DateInterval::createFromDateString($input->getOption(self::INTERVAL_AFTER)));

        try {
            $this->processor->process($cycleStartDate, $cycleEndDate, $cycleDate);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param array $cycleDays
     * @param DateTime $cronDate
     * @return DateTime
     */
    public function getCycleDate(array $cycleDays, DateTime $cronDate)
    {
        $currentDay = (int) date('j', $cronDate->getTimestamp());
        $currentMonth = (int) date('n', $cronDate->getTimestamp());
        $currentYear = (int) date('Y', $cronDate->getTimestamp());

        $month = $currentMonth;
        $year = $currentYear;

        $cycleDays = array_unique($cycleDays);
        sort($cycleDays, SORT_NUMERIC);

        $day = $currentDay;
        do {
            $day -= 1;
            if (!$day) {
                $day = date("t");
                $month -= 1;
                if ($currentMonth == 0) {
                    $month = 12;
                    $year -= 1;
                }
            }
        } while (!in_array($day, $cycleDays));

        $date = new DateTime();
        $date->setDate($year, $month, $day);
        $date->setTime((int) date('G', $cronDate->getTimestamp()), (int) date('i', $cronDate->getTimestamp()));
        return $date;
    }
}