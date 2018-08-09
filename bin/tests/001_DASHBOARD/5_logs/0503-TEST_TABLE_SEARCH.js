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
 * Test logs table search
 */
casper.test.begin('Test logs table search', function (test) {
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
            // check if logs table is displayed
            table.checkFirstPage(test, 'table_logs');
        })
        .then(function () {
            table.checkSearch(test, 'table_logs', '113', '113', 4);
        })
        .then(function () {
            table.checkSearch(test, 'table_logs', '280', 'Aucun élément à afficher', 1);
        })
        .run(function () {
            test.done();
        });
});
