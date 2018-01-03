/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

/**
 * Test vendor logs table first page
 */
casper.test.begin('Test vendor logs table first page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {
            table.checkPaginationButton(test, 'vendor logs', 3);
        })
        .then(function () {
            table.errorButtonExist(test, 'table_vendor', 'vendor-notice');
        })
        .then(function () {
            table.checkErrorMessage(test, 'table_vendor', 'vendor-notice');
        })
        .run(function () {
            test.done();
        });
});
