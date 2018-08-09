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
 * Test Transfer & withdraw logs table columns sorting
 */
casper.test.begin('Test Transfer & withdraw logs table columns sorting', function (test) {
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
            table.checkSortColumn(test, 'table_logs', 'Date', 1, '2017-12-01 15:40:132017-12-01 15:40:132017-12-01 15:40:132017-12-01 15:40:142017-12-01 15:40:142017-12-01 15:40:142017-12-01 15:40:152017-12-01 15:40:152017-12-01 15:40:152017-12-01 15:40:16');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_logs', 'Information type', 2, 'ALERTCRITICALCRITICALDEBUGDEBUGEMERGENCYERRORINFOINFOINFO');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_logs', 'Action', 3, 'Wallet creationWallet creationWallet creationWallet creationWallet creationWallet creationWallet creationWallet creation');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_logs', 'Mirakl ID', 4, '1456');
        })
        .run(function () {
            test.done();
        });
});
