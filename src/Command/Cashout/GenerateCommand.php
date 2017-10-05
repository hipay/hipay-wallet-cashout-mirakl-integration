<?php
namespace HiPay\Wallet\Mirakl\Integration\Command\Cashout;

use DateInterval;
use \DateTime;
use Exception;
use HiPay\Wallet\Mirakl\Cashout\Initializer as CashoutInitializer;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HiPay\Wallet\Mirakl\Integration\Entity\Batch;
use HiPay\Wallet\Mirakl\Integration\Entity\BatchRepository;

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
    const TRANSACTION_FILTER_REGEX = 'transactionFilterRegex';

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

    /** @var string */
    protected $transactionFilterRegex;

    protected $batchManager;

    /**
     * GenerateCommand constructor.
     * @param LoggerInterface $logger
     * @param CashoutInitializer $processor
     * @param array $cycleDays
     * @param int $cycleHour
     * @param int $cycleMinute
     * @param $cycleIntervalBefore
     * @param $cycleIntervalAfter
     * @param $transactionFilterRegex
     */
    public function __construct(
        LoggerInterface $logger,
        CashoutInitializer $processor,
        array $cycleDays,
        $cycleHour,
        $cycleMinute,
        $cycleIntervalBefore,
        $cycleIntervalAfter,
        $transactionFilterRegex,
        BatchRepository $batchManager
    )
    {

        $this->processor = $processor;
        $this->cycleDays =  is_array($cycleDays) ? $cycleDays : array($cycleDays);
        $this->cycleHour = $cycleHour;
        $this->cycleMinute = $cycleMinute;
        $this->cycleIntervalBefore = $cycleIntervalBefore;
        $this->cycleIntervalAfter = $cycleIntervalAfter;
        $this->transactionFilterRegex = $transactionFilterRegex;

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
                "A specific date consider to generate the cycle date.". "Format : YYYY-mm-dd",
                date('Y-m-d')
            )
            ->addArgument(
                self::CYCLE_DATE,
                InputArgument::OPTIONAL,
                "Directly set the cycle date." . "Format : YYYY-mm-dd"
            )
            ->addOption(
                self::INTERVAL_BEFORE,
                'b',
                InputOption::VALUE_REQUIRED,
                'Time to retire for the interval (parseable by DateInterval::createFromDateString)',
                $this->cycleIntervalBefore
            )
            ->addOption(
                self::INTERVAL_AFTER,
                'a',
                InputOption::VALUE_REQUIRED,
                'Time to add for the interval (parseable by DateInterval::createFromDateString)',
                $this->cycleIntervalAfter
            )
            ->addOption(
                self::TRANSACTION_FILTER_REGEX,
                'f',
                InputOption::VALUE_OPTIONAL,
                'The regex to test transaction number against. May be null.',
                $this->transactionFilterRegex
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batch = new Batch($this->getName());
        $this->batchManager->save($batch);

        $cronDate = new DateTime($input->getOption(self::CRON_DATE));
        $cronDate->setTime($this->cycleHour, $this->cycleMinute);

        $transactionFilterRegex = $input->getOption(self::TRANSACTION_FILTER_REGEX);

        $cycleDate = $input->getArgument(self::CYCLE_DATE);
        $cycleDate = $cycleDate ? new DateTime($cycleDate) : $this->getCycleDate($this->cycleDays, $cronDate);

        $cycleStartDate = clone $cycleDate;
        $intervalBefore = DateInterval::createFromDateString($input->getOption(self::INTERVAL_BEFORE));
        $cycleStartDate->sub($intervalBefore);
        $cycleEndDate = clone $cycleDate;
        $intervalAfter = DateInterval::createFromDateString($input->getOption(self::INTERVAL_AFTER));
        $cycleEndDate->add($intervalAfter);

        $format = '%y years %m months %d days %h hours %m minutes %i seconds';
        $this->logger->debug("Arguments : \n" .
            "Cycle date : {$cycleDate->format('Y-m-d H:i')}\n".
            "Interval before : {$intervalBefore->format($format)}\n".
            "Interval after : {$intervalAfter->format($format)}\n",
            array(
                'cycleDate' => $cycleDate,
                'intervalBefore' => $intervalBefore,
                'intervalAfter' => $intervalAfter
            )
        );
        try {
            $this->processor->process($cycleStartDate, $cycleEndDate, $cycleDate, $transactionFilterRegex);
            $batch->setEndedAt(new \DateTime());
            $this->batchManager->save($batch);
        } catch (Exception $e) {
            $batch->setError($e->getMessage());
            $this->batchManager->save($batch);

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

        $cycleDays = array_map(function ($entry) {
            if (!is_integer($entry)) {
                $entry = intval(date('j'), strtotime($entry));
            }
            return $entry;
        }, $cycleDays);

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