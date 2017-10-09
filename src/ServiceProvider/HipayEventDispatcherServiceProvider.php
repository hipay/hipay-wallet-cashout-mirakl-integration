<?php

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use HiPay\Wallet\Mirakl\Integration\Command\AbstractCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use HiPay\Wallet\Mirakl\Integration\Console\Style;

class HipayEventDispatcherServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['hipay.event.dispatcher'] = $app->share(
            function ($app) {

                $eventDispatcher = new EventDispatcher();

                $eventDispatcher->addListener(
                    ConsoleEvents::COMMAND,
                    function (ConsoleCommandEvent $event) use ($app) {
                        $command = $event->getCommand();
                        if ($app['debug'] && $command instanceof AbstractCommand) {
                            $style = new Style($event->getInput(), $event->getOutput());
                            $command->addDebugLogger($app['monolog'], $style);
                        }
                    }
                );

                return $eventDispatcher;

            }
        );
    }

    public function boot(Application $app)
    {

    }
}