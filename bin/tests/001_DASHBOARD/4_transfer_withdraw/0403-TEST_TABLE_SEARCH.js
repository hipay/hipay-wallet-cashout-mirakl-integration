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
 * Test Transfer & withdraw logs table search
 */
casper.test.begin('Test Transfer & withdraw logs table search', function (test) {
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
            // check if log Transfer & withdraw table is displayed
            table.checkFirstPage(test, 'table_transfers');
        })
        .then(function () {
            table.checkSearch(test, 'table_transfers', '13', '13', 1);
        })
        .then(function () {
            table.checkSearch(test, 'table_transfers', '280', 'Aucun élément à afficher', 1);
        })
        .run(function () {
            test.done();
        });
});
