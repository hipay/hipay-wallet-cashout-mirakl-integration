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
 * Test logs table export
 */
casper.test.begin('Test logs table export', function (test) {
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
            this.echo("Check export without filter ", "INFO");
            table.checkDownloadFile(test, 'logs.csv', '#export-action', 'Date,Level,Action,Mirakl ID,Message\n2017-12-01 15:40:13,DEBUG,,,\"Test message\"\n2017-12-01 15:40:13,DEBUG,,,\"Test message\"\n2017-12-01 15:40:13,INFO,Wallet creation,,\"Test message\"\n2017-12-01 15:40:14,INFO,Wallet creation,,\"Test message\"\n2017-12-01 15:40:14,INFO,Wallet creation,,\"Test message\"\n2017-12-01 15:40:14,INFO,Wallet creation,,\"Test message\"\n2017-12-01 15:40:15,INFO,Wallet creation,1,\"Test message\"\n2017-12-01 15:40:15,INFO,Wallet creation,5,\"Test message\"\n2017-12-01 15:40:15,NOTICE,Wallet creation,6,\"Test message\"\n2017-12-01 15:40:16,WARNING,Wallet creation,4,\"Test message\"\n2017-12-01 15:40:16,ERROR,Wallet creation,139,\"Test message\"\n2017-12-01 15:40:16,CRITICAL,Wallet creation,118,\"Test message\"\n2017-12-01 15:40:16,ALERT,Wallet creation,102,\"Test message\"\n2017-12-01 15:40:16,EMERGENCY,Wallet creation,137,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,104,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,107,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,132,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,150,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,123,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,119,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,124,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,112,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,106,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,131,\"Test message\"\n2017-12-01 15:40:16,INFO,Wallet creation,144,\"Test message\"\n2017-12-05 15:40:17,INFO,Wallet creation,120,\"Test message\"\n2017-12-05 15:40:18,INFO,Wallet creation,114,\"Test message\"\n2017-12-05 15:40:19,INFO,Wallet creation,113,\"Test message\"\n2017-12-05 15:40:20,CRITICAL,Wallet creation,127,\"Test message\"\n2017-12-05 15:40:21,INFO,Wallet creation,128,\"Test message\"');
        })
        // Going back to logs page
        .back()
        // checking with filter
        .then(function () {

            this.echo("Log level = INFO & start = 05/12/2017 & end = 05/12/2017 ", "INFO");

            var formInputs = {
                'log-level': '200',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, '2017-12-05 15:40:21INFOWallet creation128Test message2017-12-05 15:40:19INFOWallet creation113Test message2017-12-05 15:40:18INFOWallet creation114Test message2017-12-05 15:40:17INFOWallet creation120Test message');
        })
        .then(function () {
            this.echo("Check export with filters ", "INFO");
            table.checkDownloadFile(test, 'logs.csv', '#export-action', 'Date,Level,Action,Mirakl ID,Message\n2017-12-05 15:40:17,INFO,Wallet creation,120,\"Test message\"\n2017-12-05 15:40:18,INFO,Wallet creation,114,\"Test message\"\n2017-12-05 15:40:19,INFO,Wallet creation,113,\"Test message\"\n2017-12-05 15:40:21,INFO,Wallet creation,128,\"Test message\"');
        })
        // Going back to logs page
        .back()
        // checking empty results
        .then(function () {

            this.echo("Log level = INFO & start = 05/12/2020 & end = 05/12/2020 ", "INFO");

            var formInputs = {
                'log-level': '200',
                'start': '05/12/2020',
                'end': '05/12/2020'
            };

            table.checkFilter(test, 'table_logs', 'form#logs-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {
            this.echo("Check export without results ", "INFO");
            table.checkDownloadFile(test, 'logs.csv', '#export-action', 'Date,Level,Action,Mirakl ID,Message');
        })
        .run(function () {
            test.done();
        });
});
