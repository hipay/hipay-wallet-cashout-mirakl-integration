casper.test.begin('Test filling valid credentials and log ', function(test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function() {
            authentification.proceed(test);
        })
        .run(function() {
            test.done();
        });
});