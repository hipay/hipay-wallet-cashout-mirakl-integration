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
 * Test menu navigation, logged in
 */
casper.test.begin('Test menu navigation, logged in', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#vendors');
            this.waitForUrl(/dashboard\/$/, function success() {
                test.assertUrlMatch(/dashboard\/$/, "Vendors page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/$/, "Wrong page when clicking on Vendors page !");
            }, 3000);
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
            this.click('#logs');
            this.waitForUrl(/dashboard\/logs$/, function success() {
                test.assertUrlMatch(/dashboard\/logs$/, "Logs page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/logs$/, "Wrong page when clicking on Logs page !");
            }, 3000);
        })
        .then(function () {
            this.click('#settings');
            this.waitForUrl(/dashboard\/settings$/, function success() {
                test.assertUrlMatch(/dashboard\/settings$/, "Settings page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/settings$/, "Wrong page when clicking on Settings page !");
            }, 3000);
        })
        .run(function () {
            test.done();
        });
});
