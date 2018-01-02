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
 * Test vendor logs table pagination
 */
casper.test.begin('Test vendor logs table pagination', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.waitForSelector('#table_vendor > tbody > tr', function success() {
                table.changePage(test, 'table_vendor', 10, 'second');
            }, function fail() {
                test.assertElementCount('#table_vendor > tbody > tr', 10, 'first page of table not loaded');
            }, 10000);
        })
        .then(function () {
            table.changePage(test, 'table_vendor', 9, 'third');
        })
        .run(function () {
            test.done();
        });

});
