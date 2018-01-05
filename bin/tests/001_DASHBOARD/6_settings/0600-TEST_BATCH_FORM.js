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
 * Test batch form in settings page
 */
casper.test.begin('Test batch form in settings page', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#settings');
            this.waitForUrl(/dashboard\/settings$/, function success() {
                test.assertUrlMatch(/dashboard\/settings$/, "settings page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/settings$/, "Wrong page when clicking on setting page !");
            }, 3000);
        })
        .then(function () {

            this.echo("vendor:process batch", "INFO");

            var formInputs = {
                'form[batch][]': 'vendor:process'
            };
            
            settings.checkBatchForm(test, formInputs, true, /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}vendor:processEn cours /, '');
        })
        .then(function () {

            this.echo("cashout:generate batch", "INFO");

            var formInputs = {
                'form[batch][]': 'cashout:generate'
            };

            settings.checkBatchForm(test, formInputs, true, /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}cashout:generateTerminé /, '');
        })
        .then(function () {

            this.echo("vendor:process batch with date", "INFO");

            var formInputs = {
                'form[batch][]': 'vendor:process'
            };

            settings.checkBatchForm(test, formInputs, true, /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}vendor:processEn cours /, '01/01/2018');
        })
        .then(function () {

            this.echo("cashout:transfer batch", "INFO");

            var formInputs = {
                'form[batch][]': 'cashout:transfer'
            };

            settings.checkBatchForm(test, formInputs, false, /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}cashout:transferTerminé /, '');
        })
        .then(function () {

            this.echo("cashout:withdraw batch", "INFO");

            var formInputs = {
                'form[batch][]': 'cashout:withdraw'
            };

            settings.checkBatchForm(test, formInputs, false, /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}cashout:withdrawTerminé /, '');
        })
        .run(function () {
            test.done();
        });
});
