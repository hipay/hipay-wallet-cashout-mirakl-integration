casper.test.begin('Test filling valid credentials and log ', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            this.echo("Connecting to Dashboard...", "INFO");

            this.waitForSelector("#login", function success() {
                this.fillSelectors('form#login', {
                    'input[name="_username"]': admin_login,
                    'input[name="_password"]': admin_passwd
                }, false);
                this.click('#send');
                this.waitForUrl(/dashboard\/$/, function success() {
                    test.info("Connected");
                    test.assertUrlMatch(/dashboard\/$/, "Connected !");
                }, function fail() {
                    test.assertUrlMatch(/dashboard\/login$/, "Incorrect credentials !");
                }, 3000);
            }, function fail() {
                this.waitForUrl(/dashboard\/$/, function success() {
                    test.info("Already logged to admin panel !");
                }, function fail() {
                    test.assertUrlMatch(/dashboard\/$/, "Already connected");
                });
            },2000);
        })
        .run(function () {
            test.done();
        });
});