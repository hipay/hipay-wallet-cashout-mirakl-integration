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
 * Test vendor logs table search
 */
casper.test.begin('Test vendor logs table search', function (test) {
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
            table.checkSearch(test, 'table_vendor', '28', '28', 1);
        })
        .then(function () {
            table.checkSearch(test, 'table_vendor', '280', 'Aucun élément à afficher', 1);
        })
        .then(function () {
            table.checkSearch(
                test,
                'table_vendor',
                'mirakl_test_2',
                'mirakl_test_28mirakl_test_27mirakl_test_26mirakl_test_25mirakl_test_24mirakl_test_23' +
                'mirakl_test_22mirakl_test_21mirakl_test_20mirakl_test_2',
                2
            );
        })
        .run(function () {
            test.done();
        });
});
