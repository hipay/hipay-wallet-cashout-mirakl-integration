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
 * Test Transfer & withdraw logs table first page
 */
casper.test.begin('Test Transfer & withdraw logs table first page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#transfer');
            this.waitForUrl(/dashboard\/transferts$/, function success() {
                test.assertUrlMatch(/dashboard\/transferts$/, "Transfers & withdraw page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/transferts$/, "Wrong page when clicking on Transfers & withdraw page !");
            }, 3000);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_transfers');
        })
        .then(function () {
            table.checkPaginationButton(test, 'transfer & withdraw logs', 2);
        })
        .then(function () {
            table.errorButtonExist(test, 'table_transfers', 'vendor-notice');
        })
        .then(function () {
            table.checkErrorMessage(test, 'table_transfers', 'vendor-notice');
        })
        .run(function () {
            test.done();
        });
});
