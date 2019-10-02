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
 * Test Transfer & withdraw logs table pagination
 */
casper.test.begin('Test Transfer & withdraw logs table pagination', function (test) {
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
            this.waitForSelector('#table_transfers > tbody > tr', function success() {
                table.changePage(test, 'table_transfers', 8, 'second');
            }, function fail() {
                test.assertElementCount('#table_transfers > tbody > tr', 8, 'first page of table not loaded');
            }, 10000);
        })
        .run(function () {
            test.done();
        });

});
