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
 * Test technicals information in settings page
 */
casper.test.begin('Test technicals information in settings page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#settings');
            this.waitForUrl(/dashboard\/settings$/, function success() {
                test.assertUrlMatch(/dashboard\/settings$/, "settings page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/settings$/, "Wrong page when clicking on setting page !");
            }, 3000);
        })
        .then(function () {
            settings.checkTechnicalsInformation(test);
        })
        .run(function () {
            test.done();
        });
});
