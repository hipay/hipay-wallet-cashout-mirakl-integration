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
use Silex\Provider\TranslationServiceProvider as SilexTranslationServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;

class TranslationServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app->register(
            new SilexTranslationServiceProvider(), array(
            'locale_fallbacks' => array('en'),
            )
        );

        $app['translator'] = $app->share(
            $app->extend(
                'translator',
                function ($translator, $app) {
                $translator->addLoader('yaml', new YamlFileLoader());
                $translator->addResource('yaml', __DIR__.'/../../app/locales/en.yml', 'en');
                $translator->addResource('yaml', __DIR__.'/../../app/locales/fr.yml', 'fr');

                return $translator;
            }
            )
        );

        $app['translator']->setLocale($app['hipay.parameters']['dashboard.locale']);
    }

    public function boot(Application $app)
    {

    }
}