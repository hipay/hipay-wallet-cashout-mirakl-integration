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
 * Test vendor logs table filtering
 */
casper.test.begin('Test vendor logs table filtering', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            // log in to the dashboard
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            // check if log vendors table is displayed
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {

            this.echo("Empty  filter form ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28' +
                '  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués27' +
                'France2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé' +
                'Non bloqués26France2017-12-05 15:55:47  Voir le détail25mirakl_test_25Critical Afficher message' +
                'Not createdActivéNon bloqués25France2017-12-04 15:21:24  Voir le détail24mirakl_test_24Info' +
                ' Not identifiedActivéNon bloqués24France2017-12-04 10:51:30  Voir le détail23mirakl_test_23Success' +
                ' Not identifiedActivéNon bloqués23Etats-unis2017-12-04 10:51:28  Voir le détail22mirakl_test_22Success' +
                ' IdentifiedActivéNon bloqués22Etats-unis2017-12-04 10:51:25  Voir le détail21mirakl_test_21Success' +
                ' Not identifiedActivéNon bloqués21France2017-12-04 10:51:22  Voir le détail20mirakl_test_20Success' +
                ' Not identifiedActivéNon bloqués20France2017-12-04 10:51:19  Voir le détail19mirakl_test_19Success' +
                ' Not identifiedActivéNon bloqués19France2017-12-04 10:51:17  Voir le détail'
            );
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '1'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '1mirakl_test_1Success CreatedActivéNon bloqués1France2017-12-04 10:50:27  ' +
                'Voir le détail2019mirakl_test_0Success CreatedActivéNon bloqués0France2017-11-01 10:50:27' +
                '  Voir le détail'
            );
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = NOT CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '2'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '3'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '22mirakl_test_22Success IdentifiedActivéNon bloqués22Etats-unis2017-12-04 10:51:25' +
                '  Voir le détail11mirakl_test_11Success IdentifiedActivéNon bloqués11Italie2017-12-04 10:50:57' +
                '  Voir le détail7mirakl_test_7Success IdentifiedActivéNon bloqués7France2017-12-04 10:50:47' +
                '  Voir le détail'
            );
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = NOT IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '4'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '23mirakl_test_23Success Not identifiedActivéNon bloqués23Etats-unis2017-12-04 10:51:28  ' +
                'Voir le détail21mirakl_test_21Success Not identifiedActivéNon bloqués21France2017-12-04 10:51:22  ' +
                'Voir le détail20mirakl_test_20Success Not identifiedActivéNon bloqués20France2017-12-04 10:51:19  ' +
                'Voir le détail19mirakl_test_19Success Not identifiedActivéNon bloqués19France2017-12-04 10:51:17  ' +
                'Voir le détail18mirakl_test_18Success Not identifiedActivéNon bloqués18France2017-12-04 10:51:14  ' +
                'Voir le détail17mirakl_test_17Success Not identifiedActivéNon bloqués17France2017-12-04 10:51:12  ' +
                'Voir le détail16mirakl_test_16Success Not identifiedActivéNon bloqués16France2017-12-04 10:51:09  ' +
                'Voir le détail15mirakl_test_15Success Not identifiedActivéNon bloqués15France2017-12-04 10:51:07  ' +
                'Voir le détail14mirakl_test_14Success Not identifiedActivéNon bloqués14France2017-12-04 10:51:04  ' +
                'Voir le détail13mirakl_test_13Success Not identifiedActivéNon bloqués13Inconnu2017-12-04 10:51:02  ' +
                'Voir le détail'
            );
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '1'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '2'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués27France2017-12-05 16:16:37' +
                '  Voir le détail'
            );
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '3'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '4'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                'Aucun élément à afficher'
            );
        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = 05/12/2017 & end date = 05/12/2017 ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28' +
                '  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués27France' +
                '2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé' +
                'Non bloqués26France2017-12-05 15:55:47  Voir le détail'
            );

        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = 05/12/2017 & end date = EMPTY ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '05/12/2017',
                'end': ''
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28  ' +
                'Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués27France' +
                '2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé' +
                'Non bloqués26France2017-12-05 15:55:47  Voir le détail'
            );

        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = EMPTY & end date = 04/12/2017 ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '',
                'end': '04/12/2017'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '25mirakl_test_25Critical Afficher messageNot createdActivéNon bloqués25France2017-12-04 15:21:24  ' +
                'Voir le détail24mirakl_test_24Info Not identifiedActivéNon bloqués24France2017-12-04 10:51:30  ' +
                'Voir le détail23mirakl_test_23Success Not identifiedActivéNon bloqués23Etats-unis2017-12-04 10:51:28' +
                '  Voir le détail22mirakl_test_22Success IdentifiedActivéNon bloqués22Etats-unis2017-12-04 10:51:25' +
                '  Voir le détail21mirakl_test_21Success Not identifiedActivéNon bloqués21France2017-12-04 10:51:22' +
                '  Voir le détail20mirakl_test_20Success Not identifiedActivéNon bloqués20France2017-12-04 10:51:19' +
                '  Voir le détail19mirakl_test_19Success Not identifiedActivéNon bloqués19France2017-12-04 10:51:17' +
                '  Voir le détail18mirakl_test_18Success Not identifiedActivéNon bloqués18France2017-12-04 10:51:14' +
                '  Voir le détail17mirakl_test_17Success Not identifiedActivéNon bloqués17France2017-12-04 10:51:12' +
                '  Voir le détail16mirakl_test_16Success Not identifiedActivéNon bloqués16France2017-12-04 10:51:09' +
                '  Voir le détail'
            );
            
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT CREATED & start date = 05/12/2017 & end date = 05/12/2017 ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '2',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués' +
                '27France2017-12-05 16:16:37  Voir le détail'
            );
        })
        .then(function () {

            test.info("Empty form");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '',
                'end': ''
            };

            table.checkFilter(
                test,
                'table_vendor',
                'form#vendor-filter-form',
                formInputs,
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28' +
                '  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués' +
                '27France2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher message' +
                'Not createdActivéNon bloqués26France2017-12-05 15:55:47  Voir le détail25mirakl_test_25Critical' +
                ' Afficher messageNot createdActivéNon bloqués25France2017-12-04 15:21:24  Voir le détail24' +
                'mirakl_test_24Info Not identifiedActivéNon bloqués24France2017-12-04 10:51:30  Voir le détail' +
                '23mirakl_test_23Success Not identifiedActivéNon bloqués23Etats-unis2017-12-04 10:51:28' +
                '  Voir le détail22mirakl_test_22Success IdentifiedActivéNon bloqués22Etats-unis2017-12-04 10:51:25' +
                '  Voir le détail21mirakl_test_21Success Not identifiedActivéNon bloqués21France2017-12-04 10:51:22' +
                '  Voir le détail20mirakl_test_20Success Not identifiedActivéNon bloqués20France2017-12-04 10:51:19' +
                '  Voir le détail19mirakl_test_19Success Not identifiedActivéNon bloqués19France2017-12-04 10:51:17' +
                '  Voir le détail'
            );

        })
        .then(function(){
            this.echo("Country = France ", "INFO");

            this.click('.multiselect.dropdown-toggle.btn.btn-default');

            this.click(x('//label[contains(., "Italie")]'));
            this.click(x('//label[contains(., "Etats-unis")]'));
            this.click(x('//label[contains(., "Inconnu")]'));

            table.testFilter(
                test,
                'table_vendor',
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28' +
                '  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués' +
                '27France2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher message' +
                'Not createdActivéNon bloqués26France2017-12-05 15:55:47  Voir le détail25mirakl_test_25Critical' +
                ' Afficher messageNot createdActivéNon bloqués25France2017-12-04 15:21:24  Voir le détail' +
                '24mirakl_test_24Info Not identifiedActivéNon bloqués24France2017-12-04 10:51:30  Voir le détail' +
                '21mirakl_test_21Success Not identifiedActivéNon bloqués21France2017-12-04 10:51:22  Voir le détail' +
                '20mirakl_test_20Success Not identifiedActivéNon bloqués20France2017-12-04 10:51:19  ' +
                'Voir le détail19mirakl_test_19Success Not identifiedActivéNon bloqués19France2017-12-04 10:51:17' +
                '  Voir le détail18mirakl_test_18Success Not identifiedActivéNon bloqués18France2017-12-04 10:51:14' +
                '  Voir le détail17mirakl_test_17Success Not identifiedActivéNon bloqués17France2017-12-04 10:51:12' +
                '  Voir le détail'
            );

        })
        .then(function(){
            this.echo("Country = Inconnu ", "INFO");

            this.click('.multiselect.dropdown-toggle.btn.btn-default');

            this.click(x('//label[contains(., "France")]'));
            this.click(x('//label[contains(., "Inconnu")]'));

            table.testFilter(
                test,
                'table_vendor',
                '13mirakl_test_13Success Not identifiedActivéNon bloqués13Inconnu2017-12-04 10:51:02  ' +
                'Voir le détail12mirakl_test_12Success Not identifiedActivéNon bloqués12Inconnu2017-12-04 10:50:59  ' +
                'Voir le détail'
            );

        })
        .then(function(){
            this.echo("Country = FRANCE ITALIA USA ", "INFO");

            this.click('.multiselect.dropdown-toggle.btn.btn-default');

            this.click(x('//label[contains(., "France")]'));
            this.click(x('//label[contains(., "Inconnu")]'));
            this.click(x('//label[contains(., "Etats-unis")]'));
            this.click(x('//label[contains(., "Italie")]'));

            table.testFilter(
                test,
                'table_vendor',
                '28mirakl_test_28Critical Afficher messageNot createdActivéNon bloqués28France2017-12-05 16:18:28' +
                '  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivéNon bloqués27' +
                'France2017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot created' +
                'ActivéNon bloqués26France2017-12-05 15:55:47  Voir le détail25mirakl_test_25Critical ' +
                'Afficher messageNot createdActivéNon bloqués25France2017-12-04 15:21:24  ' +
                'Voir le détail24mirakl_test_24Info Not identifiedActivéNon bloqués24France2017-12-04 10:51:30' +
                '  Voir le détail23mirakl_test_23Success Not identifiedActivéNon bloqués23Etats-unis' +
                '2017-12-04 10:51:28  Voir le détail22mirakl_test_22Success IdentifiedActivéNon bloqués22' +
                'Etats-unis2017-12-04 10:51:25  Voir le détail21mirakl_test_21Success Not identifiedActivé' +
                'Non bloqués21France2017-12-04 10:51:22  Voir le détail20mirakl_test_20Success Not identifiedActivé' +
                'Non bloqués20France2017-12-04 10:51:19  Voir le détail19mirakl_test_19Success Not identifiedActivé' +
                'Non bloqués19France2017-12-04 10:51:17  Voir le détail'
            );

        })
        .run(function () {
            test.done();
        });
});
