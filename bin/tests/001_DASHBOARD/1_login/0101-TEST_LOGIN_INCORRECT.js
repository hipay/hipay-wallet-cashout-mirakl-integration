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
                this.waitForUrl(/dashboard\/$/, function success() {
                    test.assertUrlMatch(/dashboard\/login$/, "Incorrect credentials but logged anyway!");
                }, function fail() {
                    test.info("Success : Not Connected !");
                    test.assertUrlMatch(/dashboard\/login$/, "Not Connected with wrong credentials !");
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
