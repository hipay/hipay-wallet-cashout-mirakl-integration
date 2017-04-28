<?php
namespace HiPay\Wallet\Mirakl\Integration\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * File Logger.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class Logger implements LoggerInterface
{
    /** @var  SymfonyStyle */
    protected $outputStyle;

    protected $title = false;
    /**
     * ConsoleLogger constructor.
     * @param SymfonyStyle $outputStyle
     */
    public function __construct(SymfonyStyle $outputStyle)
    {
        $this->outputStyle = $outputStyle;
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->outputStyle->error($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        $this->outputStyle->error($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        $this->outputStyle->error($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        $this->outputStyle->error($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        $this->outputStyle->warning($message);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        $this->outputStyle->note($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        if (!$this->title) {
            $this->outputStyle->title($message);
            $this->title = true;
            return;
        }
        if (strpos($message, '[OK]') !== false) {
            $this->outputStyle->success($message);
        } else {
            $this->outputStyle->section($message);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        $this->outputStyle->comment($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $level = strtolower($level);

        $this->$level($message, $context);
    }

    public function __call($name, $arguments)
    {
        $this->outputStyle->writeln(current($arguments));
    }
}