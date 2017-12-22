casper.test.begin('Test vendor logs table columns sorting', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Mirakl ID', 1, '12345678910');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Login', 2, 'mirakl_test_1mirakl_test_10mirakl_test_11mirakl_test_12mirakl_test_13mirakl_test_14mirakl_test_15mirakl_test_16mirakl_test_17mirakl_test_18');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Status', 3, 'Success Success Success Success Success Success Success Success Success Success ');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Wallet account status', 4, 'CreatedNot createdNot createdNot createdNot createdNot createdIdentifiedIdentifiedIdentifiedNot identified');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Enabled', 5, 'ActivéActivéActivéActivéActivéActivéActivéActivéActivéActivé');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'HiPay ID', 6, '12345678910');
        })
        .then(function () {
            table.checkSortColumn(test, 'table_vendor', 'Date', 7, '2017-12-04 10:50:272017-12-04 10:50:312017-12-04 10:50:342017-12-04 10:50:372017-12-04 10:50:412017-12-04 10:50:452017-12-04 10:50:472017-12-04 10:50:502017-12-04 10:50:522017-12-04 10:50:55');
        })
        .run(function () {
            test.done();
        });

});
