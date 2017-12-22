casper.test.begin('Test vendor logs table page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {
            table.checkPaginationButton(test, 'vendor logs', 3);
        })
        .then(function () {
            table.errorButtonExist(test, 'table_vendor', 'vendor-notice');
        })
        .then(function () {
            table.checkErrorMessage(test, 'table_vendor', 'vendor-notice');
        })
        .run(function () {
            test.done();
        });
});
