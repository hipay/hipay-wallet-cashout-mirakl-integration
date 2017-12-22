casper.test.begin('Test vendor logs table filtering', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {

            var formInputs = {
                'status': '1',
                'wallet_status': '1'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '1mirakl_test_1Success CreatedActivé12017-12-04 10:50:27  Voir le détail');
        })
        .then(function () {

            var formInputs = {
                'status': '3',
                'wallet_status': '2'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail');
        })
        .run(function () {
            test.done();
        });
});
