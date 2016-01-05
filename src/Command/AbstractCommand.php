<?php
namespace Hipay\SilexIntegration\Command;
use Doctrine\ORM\EntityManagerInterface;
use Hipay\SilexIntegration\Configuration\ParameterAccessor;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;

/**
 * File AbstractCommand.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
abstract class AbstractCommand extends Command
{
    const DEFAULT_LOG_PATH = "/var/log/hipay.log";

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var LoggerInterface */
    protected $logger;

    /** @var  Swift_Mailer */
    protected $swiftMailer;

    /**
     * AbstractCommand constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $swiftTransport = new \Swift_SmtpTransport(
            ParameterAccessor::getParameter('mail.host'),
            ParameterAccessor::getParameter('mail.port'),
            ParameterAccessor::getParameter('mail.security')
        );
        $this->swiftMailer = new Swift_Mailer($swiftTransport);

        $this->logger = new Logger(__NAMESPACE__ . '\\' . get_called_class());
        $this->logger->pushHandler(new StreamHandler(ParameterAccessor::getParameter('log.file.path') ?: self::DEFAULT_LOG_PATH));
        $messageTemplate = new \Swift_Message();
        $messageTemplate->setSubject(ParameterAccessor::getParameter('mail.subject'));
        $messageTemplate->setTo(ParameterAccessor::getParameter('mail.to'));
        $messageTemplate->setFrom(ParameterAccessor::getParameter('mail.from'));
        $messageTemplate->setCharset('utf-8');
        $this->logger->pushHandler(new SwiftMailerHandler($this->swiftMailer, $messageTemplate));
    }

}