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
 * Test menu navigation, logged out
 */
casper.test.begin('Test menu navigation, logged out', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            this.click('#vendors');
            this.waitForUrl(/dashboard\/login$/, function success() {
                test.assertUrlMatch(/dashboard\/login$/, "Redirect to login page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/login$/, "Error : Should be redirect to login page. !");
            }, 3000);
        })
        .then(function () {
            this.click('#transfer');
            this.waitForUrl(/dashboard\/login$/, function success() {
                test.assertUrlMatch(/dashboard\/login$/, "Redirect to login page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/login$/, "Error : Should be redirect to login page. !");
            }, 3000);
        })
        .then(function () {
            this.click('#logs');
            this.waitForUrl(/dashboard\/login$/, function success() {
                test.assertUrlMatch(/dashboard\/login$/, "Redirect to login page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/login$/, "Error : Should be redirect to login page. !");
            }, 3000);
        })
        .then(function () {
            this.click('#settings');
            this.waitForUrl(/dashboard\/login$/, function success() {
                test.assertUrlMatch(/dashboard\/login$/, "Redirect to login page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/login$/, "Error : Should be redirect to login page. !");
            }, 3000);
        })
        .run(function () {
            test.done();
        });
});
