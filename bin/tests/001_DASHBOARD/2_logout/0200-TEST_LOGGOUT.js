casper.test.begin('Test logging out after log in ', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#logout');
            this.waitForUrl(/dashboard\/login$/, function success() {
                test.info("Logged out");
                test.assertUrlMatch(/dashboard\/login$/, "Logged out !");
            }, function fail(){
                test.fail("Not logged out");
            }, 3000);
        })
        .run(function () {
            test.done();
        });
});
