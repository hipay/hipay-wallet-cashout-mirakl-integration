<?php

namespace HiPay\Wallet\Mirakl\Integration\Handler;

use HiPay\Wallet\Mirakl\Integration\Entity\LogGeneral;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class MonologDBHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * MonologDBHandler constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, $level)
    {
        parent::__construct($level);
        $this->em = $em;
    }

    /**
     * Called when writing to our database
     * @param array $record
     */
    protected function write(array $record)
    {

        if ('doctrine' == $record['channel']) {
            error_log($record['message']);
            return;
        }
        try {
            $logEntry = new LogGeneral();
            $logEntry->setMessage($record['message']);
            $logEntry->setLevel($record['level']);
            $logEntry->setLevelName($record['level_name']);
            $logEntry->setExtra($record['extra']);
            $logEntry->setContext($record['context']);

            if (isset($record['context']["miraklId"])) {
                $logEntry->setMiraklId($record['context']["miraklId"]);
            }

            if (isset($record['context']["action"])) {
                $logEntry->setAction($record['context']["action"]);
            }

            $this->em->persist($logEntry);
            $this->em->flush();
        } catch (\Exception $e) {

            // Fallback to just writing to php error logs if something really bad happens
            error_log($record['message']);
            error_log($e->getMessage());
        }
    }
}