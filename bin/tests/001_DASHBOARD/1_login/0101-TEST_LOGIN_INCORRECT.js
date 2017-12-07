casper.test.begin('Test filling invalid credentials and log ', function (test) {
    phantom.clearCookies();

    var user = 'test',
        password = 'test';

    casper.start(baseURL)
        .then(function () {
            this.echo("Connecting to Dashboard...", "INFO");

            this.waitForSelector("#login", function success() {
                this.fillSelectors('form#login', {
                    'input[name="_username"]': user,
                    'input[name="_password"]': password
                }, false);
                this.click('#send');
                this.waitForUrl(/dashboard\/login$/, function success() {
                    test.info("Not Connected");
                    test.assertUrlMatch(/dashboard\/login$/, "Not Connected !");
                }, function fail() {
                    test.fail("Incorrect credentials but logged anyway!");
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
