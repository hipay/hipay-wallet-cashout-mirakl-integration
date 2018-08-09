<?php
/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2016 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

namespace HiPay\Wallet\Mirakl\Integration\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;

class RepositoriesServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['vendors.repository'] = $app->share(function ($app) {
            return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Vendor');
        });

        $app['document.repository'] = $app->share(function ($app) {
            return $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Document');
        });

        $app['batch.repository'] = $app->share(function ($app) {
            return $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Batch');
        });

        $app['operations.repository'] = $app->share(function ($app) {
            $operationRepository = $app["orm.em"]->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Operation');
            $operationRepository->setPublicLabelTemplate($app['hipay.parameters']['label.public']);
            $operationRepository->setPrivateLabelTemplate($app['hipay.parameters']['label.private']);
            $operationRepository->setWithdrawLabelTemplate($app['hipay.parameters']['label.withdraw']);

            return $operationRepository;
        });

        $app['log.vendors.repository'] = $app->share(function ($app) {
            return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogVendors');
        });

        $app['log.operations.repository'] = $app->share(function ($app) {
            return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogOperations');
        });

        $app['log.general.repository'] = $app->share(function ($app) {
            return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\LogGeneral');
        });

        $app['batch.repository'] = $app->share(function ($app) {
            return $app['orm.em']->getRepository('HiPay\\Wallet\\Mirakl\\Integration\\Entity\\Batch');
        });
    }

    public function boot(Application $app)
    {

    }
}