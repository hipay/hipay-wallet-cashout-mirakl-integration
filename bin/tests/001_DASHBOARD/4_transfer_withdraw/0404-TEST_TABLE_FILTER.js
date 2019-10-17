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
 * Test Transfer & withdraw logs table filtering
 */
casper.test.begin('Test Transfer & withdraw logs table filtering', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            this.click('#transfer');
            this.waitForUrl(/dashboard\/transferts$/, function success() {
                test.assertUrlMatch(/dashboard\/transferts$/, "Transfers & withdraw page !");
            }, function fail() {
                test.assertUrlMatch(/dashboard\/transferts$/, "Wrong page when clicking on Transfers & withdraw page !");
            }, 3000);
        })
        .then(function () {
            // check if log Transfer & withdraw table is displayed
            table.checkFirstPage(test, 'table_transfers');
        })
        .then(function () {

            this.echo("Empty  filter form ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '16162302151616 KO (paiement bloqué) Afficher message2552017-12-08 11:00:0015152402071616OK (demandé)' +
                '  2552017-12-08 10:59:54131323019593.5993.59KO Afficher message ' +
                '33233.192017-12-08 10:59:52141423019593.5993.59KO (Vendeur désactivé) Afficher message' +
                'KO (Vendeur désactivé) Afficher message33233.192017-12-08 10:59:52112302061515  ' +
                '2552017-12-08 10:59:5111112302051414  2552017-12-08 10:59:5010102302041313  ' +
                '2552017-12-08 10:59:4982302031212  2552017-12-08 10:59:48882302021111 KO (Fonds insuffisants)' +
                ' 02017-12-08 10:59:47772302011010OK KO (annulé) Afficher message2552017-12-08 10:59:46'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = OK (requested) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '5'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '223019655OK OK (demandé) 409.212017-12-08 10:59:41123019512.4112.41OK OK' +
                ' (demandé) 409.212017-12-08 10:59:33'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = OK ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '423019988OK OK 2552017-12-08 10:59:44'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = KO (failed) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-7'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '6623020099OK KO (échec) Afficher message2552017-12-08 10:59:45'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = KO (Insufficient funds) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-11'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '882302021111 KO (Fonds insuffisants) 02017-12-08 10:59:47'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = KO (canceled) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-8'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '772302011010OK KO (annulé) Afficher message2552017-12-08 10:59:46'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = KO (vendors disabled) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '141423019593.5993.59KO (Vendeur désactivé) Afficher messageKO (Vendeur désactivé) ' +
                'Afficher message33233.192017-12-08 10:59:52'
            );
        })
        .then(function () {

            this.echo("Status transfer = empty & status withdraw = KO (payment blocked) ", "INFO");

            var formInputs = {
                'status-transfer': '-1',
                'status-withdraw': '-12'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '16162302151616 KO (paiement bloqué) Afficher message2552017-12-08 11:00:00'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = OK (requested) ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '5'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '223019655OK OK (demandé) 409.212017-12-08 10:59:41123019512.4112.41OK' +
                ' OK (demandé) 409.212017-12-08 10:59:33'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = OK ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '423019988OK OK 2552017-12-08 10:59:44'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = KO (failed) ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '-7'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '6623020099OK KO (échec) Afficher message2552017-12-08 10:59:45'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = KO (Insufficient funds) ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '-11'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = KO (canceled) ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '-8'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '772302011010OK KO (annulé) Afficher message2552017-12-08 10:59:46'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = KO (vendors disabled) ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '-6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK & status withdraw = empty ", "INFO");

            var formInputs = {
                'status-transfer': '3',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '772302011010OK KO (annulé) Afficher message2552017-12-08 10:59:466623020099OK KO (échec)' +
                ' Afficher message2552017-12-08 10:59:45423019988OK OK 2552017-12-08 10:59:44223019655OK' +
                ' OK (demandé) 409.212017-12-08 10:59:41123019512.4112.41OK OK (demandé) 409.212017-12-08 10:59:33'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = OK (requested) ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '5'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = OK ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = KO (failed) ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '-7'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = KO (Insufficient funds) ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '-11'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = KO (canceled) ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '-8'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = KO (vendors disabled) ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '-6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (Insufficient funds) & status withdraw = empty ", "INFO");

            var formInputs = {
                'status-transfer': '-10',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '4423019877KO (Fonds insuffisants)  02017-12-08 10:59:43'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = OK (requested) ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '5'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = OK ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = KO (failed) ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '-7'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = KO (Insufficient funds) ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '-11'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = KO (canceled) ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '-8'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = KO (vendors disabled) ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '-6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO & status withdraw = empty ", "INFO");

            var formInputs = {
                'status-transfer': '-9',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '131323019593.5993.59KO Afficher message 33233.192017-12-08 10:59:52222301962727KO ' +
                'Afficher message 5266.602017-12-08 10:59:07'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = OK (requested) ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '5'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = OK ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = KO (failed) ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '-7'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = KO (Insufficient funds) ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '-11'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = KO (canceled) ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '-8'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = KO (vendors disabled) ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '-6'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '141423019593.5993.59KO (Vendeur désactivé) Afficher messageKO ' +
                '(Vendeur désactivé) Afficher message33233.192017-12-08 10:59:52'
            );
        })
        .then(function () {

            this.echo("Status transfer = KO (vendors disabled) & status withdraw = empty ", "INFO");

            var formInputs = {
                'status-transfer': '-5',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '141423019593.5993.59KO (Vendeur désactivé) Afficher messageKO (Vendeur désactivé)' +
                ' Afficher message33233.192017-12-08 10:59:52'
            );
        })
        .then(function () {

            this.echo("Status transfer = OK (requested) & status withdraw = empty ", "INFO");

            var formInputs = {
                'status-transfer': '4',
                'status-withdraw': '-1'
            };

            table.checkFilter(
                test,
                'table_transfers',
                'form#operations-filter-form',
                formInputs,
                '15152402071616OK (demandé)  2552017-12-08 10:59:54'
            );
        })
        .run(function () {
            test.done();
        });
});
