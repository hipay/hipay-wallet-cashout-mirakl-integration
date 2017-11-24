casper.test.begin('Test filling valid credentials and log ', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            this.echo("Connecting to Dashboard...", "INFO");

            this.waitForSelector("#login", function success() {
                this.fillSelectors('form#login', {
                    'input[name="_username"]': '98a3983a428dfd65f9fc0c914184e4c3',
                    'input[name="_password"]': 'ad1f38a4b13bed5f716c9d5c51bc8c92'
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