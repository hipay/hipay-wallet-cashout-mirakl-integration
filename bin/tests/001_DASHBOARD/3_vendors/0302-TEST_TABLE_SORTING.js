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
 * Test vendor logs table columns sorting
 */
casper.test.begin('Test vendor logs table columns sorting', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            // check if log vendors table is displayed
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Mirakl ID', 1, '12345678910');
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Login',
                2,
                'mirakl_test_0mirakl_test_1mirakl_test_10mirakl_test_11mirakl_test_12mirakl_test_13' +
                'mirakl_test_14mirakl_test_15mirakl_test_16mirakl_test_17'
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Status',
                3,
                'Success Success Success Success Success Success Success Success Success Success '
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Wallet account status',
                4,
                'CreatedCreatedNot createdNot createdNot createdNot createdNot createdIdentifiedIdentifiedIdentified'
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Enabled',
                5,
                'ActivéActivéActivéActivéActivéActivéActivéActivéActivéActivé'
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Enabled',
                6,
                'Non bloquésNon bloquésNon bloquésNon bloquésNon bloquésNon bloquésNon bloqués' +
                'Non bloquésNon bloquésNon bloqués'
            );
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'HiPay ID', 7, '0123456789');
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Country',
                8,
                'InconnuInconnuFranceFranceFranceFranceFranceFranceFranceFrance'
            );
        })
        .then(function () {
            table.checkSortColumn(
                test,
                'table_vendor',
                'Date',
                9,
                '2017-11-01 10:50:272017-12-04 10:50:272017-12-04 10:50:312017-12-04 10:50:342017-12-04' +
                ' 10:50:372017-12-04 10:50:412017-12-04 10:50:452017-12-04 10:50:472017-12-04 10:50:502017-12-04 10:50:52'
            );
        })
        .run(function () {
            test.done();
        });
});
