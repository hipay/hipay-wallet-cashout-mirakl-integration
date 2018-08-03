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
 * Test logs table pagination
 */
casper.test.begin('Test logs table pagination', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#logs');
            this.waitForUrl(/dashboard\/logs$/, function success() {
                test.assertUrlMatch(/dashboard\/logs$/, "Logs page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/logs$/, "Wrong page when clicking on Logs page !");
            }, 3000);
        })
        .then(function () {
            this.waitForSelector('#table_logs > tbody > tr', function success() {
                table.changePage(test, 'table_logs', 10, 'second');
            }, function fail() {
                test.assertElementCount('#table_logs > tbody > tr', 10, 'second page of table not loaded');
            }, 10000);
        })
        .then(function () {
            this.waitForSelector('#table_logs > tbody > tr', function success() {
                table.changePage(test, 'table_logs', 10, 'third');
            }, function fail() {
                test.assertElementCount('#table_logs > tbody > tr', 10, 'third page of table not loaded');
            }, 10000);
        })
        .run(function () {
            test.done();
        });

});
