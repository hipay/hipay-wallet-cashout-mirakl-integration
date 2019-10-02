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
            table.checkSortColumn(test, 'table_transfers', 'Mirakl ID', 1, '12346');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_transfers', 'HiPay ID', 2, '1122344678');
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_transfers',
                'Payment voucher ID',
                3,
                '230195230195230195230196230196230197230198230199230200230201'
            );
        })
        .then(function () {
            table.checkSortColumn(test, 'table_transfers', 'Amount', 4, '5678910111212.4113');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_transfers', 'Origin amount', 5, '5678910111212.4113');
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_transfers',
                'Transfer status',
                6,
                'KO (Fonds insuffisants) KO Afficher messageKO Afficher messageKO ' +
                '(Vendeur désactivé) Afficher message      '
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_transfers',
                'Withdraw status',
                7,
                '         KO (paiement bloqué) Afficher message'
            );
        })
        .then(function () {
            table.checkSortColumn(test, 'table_transfers', 'Balance', 8, '00255255255255255255255255');
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_transfers',
                'Date',
                9,
                '2017-12-08 10:58:322017-12-08 10:59:072017-12-08 10:59:332017-12-08 10:59:412017-12-08' +
                ' 10:59:422017-12-08 10:59:432017-12-08 10:59:442017-12-08 10:59:452017-12-08' +
                ' 10:59:462017-12-08 10:59:47'
            );
        })
        .run(function () {
            test.done();
        });
});
