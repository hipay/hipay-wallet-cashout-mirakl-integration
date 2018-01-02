casper.test.begin('Test vendor logs table page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.waitForSelector('#table_vendor > tbody > tr', function success() {
                table.changePage(test, 'table_vendor', 10, 'second');
            }, function fail() {
                test.assertElementCount('#table_vendor > tbody > tr', 10, 'first page of table not loaded');
            }, 10000);
        })
        .then(function () {
            table.changePage(test, 'table_vendor', 9, 'third');
        })
        .run(function () {
            test.done();
        });

});
