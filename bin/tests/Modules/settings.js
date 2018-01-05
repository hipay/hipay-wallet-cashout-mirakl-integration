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
 * Settings functions
 */

/**
 *
 * @param test
 * @param formInputs
 * @param dateModal
 * @param matchPattern
 * @param date
 */
exports.checkBatchForm = function checkBatchForm(test, formInputs, dateModal, matchPattern, date) {
    casper.then(function () {

        this.echo("Checking batch form...", "INFO");

        this.evaluate(function () {
            document.querySelector('#success-message-batch').remove();
        });
        this.fill('#batch-form', formInputs);
        this.click('#batch-send');

        if (dateModal === true) {
            fillingDate(test, this, matchPattern, date);
        } else {
            checkBatchRunning(test, casper, matchPattern);
        }

    });
};

/**
 *
 * @param test
 */
exports.checkTechnicalsInformation = function checkTechnicalsInformation(test) {
    casper.then(function () {
        this.echo("Checking technicals information...", "INFO");

        checkVersion(test, '#connector-version', fs.workingDirectory + '/composer.json');
        //Not working right now
        //checkVersion(test, '#library-version', fs.workingDirectory + '/vendor/hipay/hipay-wallet-cashout-mirakl-library/composer.json');
    });
};

exports.checkSettingsForm = function checkSettingsForm(test, formInputs) {
    casper.then(function () {
        this.echo("Checking settings form...", "INFO");

        this.evaluate(function () {
            document.querySelector('#success-message-settings').remove();
        });

        if (Object.keys(formInputs).length) {
            this.echo("Filling settings form", "INFO");

            this.fill('#settings-form', formInputs);
            this.click("#settings-send");
            this.capture("test.png");

            casper.waitForSelector('#success-message-settings', function success() {
                testSettingsValues(test)
            }, function fail() {
                test.assertExists('#success-message-settings', 'Success message does not exist');
            }, 10000);
        }else{
            testSettingsValues(test)
        }

    });
};


function testSettingsValues(test) {

    var parameters = YAML.load(fs.workingDirectory + '/config/parameters.yml');

    var githubToken = null;

    if (casper.fetchText('#form_token').replace(/\s/g, '') !== "") {
        githubToken = casper.fetchText('#form_token').replace(/\s/g, '');
    }

    test.assertEqual(parameters.parameters['github.token'], githubToken, 'github token is correct');
    test.assertEqual(parameters.parameters['email.logger.alert.level'], parseInt(casper.getElementAttribute('#form_email_log_level > option:checked', 'value')), 'Email log level is correct');
}

/**
 *
 * @param test
 * @param pId
 * @param path
 */
function checkVersion(test, pId, path) {
    var displayedVersion = casper.fetchText(pId).replace(/\s/g, '');
    var realVersion = getVersion(path);

    var regex = new RegExp(realVersion);

    test.assertMatch(displayedVersion, regex, "Version match");
}

/**
 *
 * @param path
 * @returns {*}
 */
function getVersion(path) {

    var data = fs.read(path);
    var jsonContent = JSON.parse(data);

    return jsonContent.version;
}

/**
 *
 * @param test
 * @param casper
 * @param matchPattern
 * @param date
 */
function fillingDate(test, casper, matchPattern, date) {
    casper.waitUntilVisible('#date-modal', function success() {
        casper.click('#modal_send');
        casper.waitForSelector('#success-message-batch', function success() {
            checkBatchRunning(test, casper, matchPattern);
        }, function fail() {
            test.assertExists('#success-message-batch', 'Success message does not exist');
        }, 10000);
    }, function fail() {
        test.assertVisible('#modal_send', 'Modal not showing');
    }, 10000);
}

/**
 *
 * @param test
 * @param casper
 * @param matchPattern
 */
function checkBatchRunning(test, casper, matchPattern) {
    casper.waitForSelector('#table_batchs > tbody > tr', function success() {
        casper.wait(5001, function () {
            casper.echo("Just waiting a bit");

            this.waitWhileVisible('#table_batchs_processing', function success() {
                var value = casper.fetchText('#table_batchs > tbody > tr:nth-child(1)');
                test.assertMatch(value, matchPattern, "Executed batch is running");
            }, function fail() {
                var value = casper.fetchText('#table_batchs > tbody > tr:nth-child(1)');
                test.assertMatch(value, matchPattern, "Executed batch is running");
            }, 10000);

        });
    }, function fail() {
        var value = casper.fetchText('#table_batchs > tbody > tr:nth-child(1)');
        test.assertMatch(value, matchPattern, "Executed batch not running");
    }, 10000);
}
