casper.test.begin('Test filling valid credentials and log ', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .run(function () {
            test.done();
        });
});
