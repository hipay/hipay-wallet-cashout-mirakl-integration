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

$app = require_once __DIR__.'/../app/bootstrap.php';

require __DIR__.'/../app/controllers.php';

$app['http_cache']->run();

