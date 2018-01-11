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
 * Test logs table filtering
 */
casper.test.begin('Test logs table filtering', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#logs');
            this.waitForUrl(/dashboard\/logs$/, function success() {
                test.assertUrlMatch(/dashboard\/logs$/, "Logs page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/logs$/, "Wrong page when clicking on Logs page !");
            }, 3000);
        })
        .then(function () {
            // check if logs table is displayed
            table.checkFirstPage(test, 'table_logs');
        })
        .then(function () {

            this.echo("Empty filter form ", "INFO");

            var formInputs = {
                'log-level': '-1',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:20CRITICALWallet creation127Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message2017-12-01 15:40:16WARNINGWallet creation4Test message2017-12-01 15:40:16ERRORWallet creation139Test message2017-12-01 15:40:16CRITICALWallet creation118Test message2017-12-01 15:40:16ALERTWallet creation102Test message2017-12-01 15:40:16EMERGENCYWallet creation137Test message');
        })
        .then(function () {

            this.echo("Log level = DEBUG & date empty ", "INFO");

            var formInputs = {
                'log-level': '100',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:13DEBUGTest message2017-12-01 15:40:13DEBUGTest message');
        })
        .then(function () {

            this.echo("Log level = INFO & date empty ", "INFO");

            var formInputs = {
                'log-level': '200',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message2017-12-01 15:40:16INFOWallet creation104Test message2017-12-01 15:40:16INFOWallet creation107Test message2017-12-01 15:40:16INFOWallet creation132Test message2017-12-01 15:40:16INFOWallet creation150Test message2017-12-01 15:40:16INFOWallet creation123Test message2017-12-01 15:40:16INFOWallet creation119Test message');
        })
        .then(function () {

            this.echo("Log level = NOTICE & date empty ", "INFO");

            var formInputs = {
                'log-level': '250',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:15NOTICEWallet creation6Test message');
        })
        .then(function () {

            this.echo("Log level = WARNING & date empty ", "INFO");

            var formInputs = {
                'log-level': '300',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:16WARNINGWallet creation4Test message');
        })
        .then(function () {

            this.echo("Log level = ERROR & date empty ", "INFO");

            var formInputs = {
                'log-level': '400',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:16ERRORWallet creation139Test message');
        })
        .then(function () {

            this.echo("Log level = CRITICAL & date empty ", "INFO");

            var formInputs = {
                'log-level': '500',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:20CRITICALWallet creation127Test message2017-12-01 15:40:16CRITICALWallet creation118Test message');
        })
        .then(function () {

            this.echo("Log level = ALERT & date empty ", "INFO");

            var formInputs = {
                'log-level': '550',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:16ALERTWallet creation102Test message');
        })
        .then(function () {

            this.echo("Log level = EMERGENCY & date empty ", "INFO");

            var formInputs = {
                'log-level': '600',
                'start': '',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:16EMERGENCYWallet creation137Test message');
        })
        .then(function () {

            this.echo("Log level = empty & start = 05/12/2017 & end = 05/12/2017 ", "INFO");

            var formInputs = {
                'log-level': '-1',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:20CRITICALWallet creation127Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message');
        })
        .then(function () {

            this.echo("Log level = empty & start = 05/12/2017 & end = empty ", "INFO");

            var formInputs = {
                'log-level': '-1',
                'start': '05/12/2017',
                'end': ''
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:20CRITICALWallet creation127Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message');
        })
        .then(function () {

            this.echo("Log level = empty & start =  & end = 01/12/2017 ", "INFO");

            var formInputs = {
                'log-level': '-1',
                'start': '',
                'end': '01/12/2017'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-01 15:40:16WARNINGWallet creation4Test message2017-12-01 15:40:16ERRORWallet creation139Test message2017-12-01 15:40:16CRITICALWallet creation118Test message2017-12-01 15:40:16ALERTWallet creation102Test message2017-12-01 15:40:16EMERGENCYWallet creation137Test message2017-12-01 15:40:16INFOWallet creation104Test message2017-12-01 15:40:16INFOWallet creation107Test message2017-12-01 15:40:16INFOWallet creation132Test message2017-12-01 15:40:16INFOWallet creation150Test message2017-12-01 15:40:16INFOWallet creation123Test message');
        })
        .then(function () {

            this.echo("Log level = WARNING & start = 05/12/2017 & end = 05/12/2017 ", "INFO");

            var formInputs = {
                'log-level': '300',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {

            this.echo("Log level = INFO & start = 05/12/2017 & end = 05/12/2017 ", "INFO");

            var formInputs = {
                'log-level': '200',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message');
        })
        .run(function () {
            test.done();
        });
});
