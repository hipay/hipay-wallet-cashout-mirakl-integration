/**
 * 2017 HiPay
 *
 * NOTICE OF LICENSE
 *
 * @author    HiPay <support.wallet@hipay.com>
 * @copyright 2017 HiPay
 * @license   https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
 */

/**
 * Authentication function
 */
exports.proceed = function proceed(test, user, password) {
    /* Connection to admin panel */
    casper.then(function () {
        this.echo("Connecting to Dashboard...", "INFO");
        this.waitForSelector("#login", function success() {
            this.fillSelectors('form#login', {
                'input[name="_username"]': user,
                'input[name="_password"]': password
            }, false);
            this.click('#send');
            this.waitForUrl(/dashboard\/$/, function success() {
                test.info("Connected");
                test.assertUrlMatch(/dashboard\/$/, "Connected !");
            }, function fail() {
                //test.fail("Incorrect credentials !");
                test.assertUrlMatch(/dashboard\/$/, "Incorrect credentials !");
            }, 3000);
        }, function fail() {
            this.waitForUrl(/dashboard\/$/, function success() {
                test.info("Already logged to admin panel !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/$/, "Already connected");
            });
        },2000);
    });
};
