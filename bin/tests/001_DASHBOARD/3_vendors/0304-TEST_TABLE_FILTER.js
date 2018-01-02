casper.test.begin('Test vendor logs table filtering', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {

            this.echo("Empty  filter form ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '28mirakl_test_28Critical Afficher messageNot createdActivé282017-12-05 16:18:28  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé262017-12-05 15:55:47  Voir le détail25mirakl_test_25Critical Afficher messageNot createdActivé252017-12-04 15:21:24  Voir le détail24mirakl_test_24Info Not identifiedActivé242017-12-04 10:51:30  Voir le détail23mirakl_test_23Success Not identifiedActivé232017-12-04 10:51:28  Voir le détail22mirakl_test_22Success IdentifiedActivé222017-12-04 10:51:25  Voir le détail21mirakl_test_21Success Not identifiedActivé212017-12-04 10:51:22  Voir le détail20mirakl_test_20Success Not identifiedActivé202017-12-04 10:51:19  Voir le détail19mirakl_test_19Success Not identifiedActivé192017-12-04 10:51:17  Voir le détail');
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '1'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '1mirakl_test_1Success CreatedActivé12017-12-04 10:50:27  Voir le détail2019mirakl_test_0Success CreatedActivé02017-11-01 10:50:27  Voir le détail');
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = NOT CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '2'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '3'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '22mirakl_test_22Success IdentifiedActivé222017-12-04 10:51:25  Voir le détail11mirakl_test_11Success IdentifiedActivé112017-12-04 10:50:57  Voir le détail7mirakl_test_7Success IdentifiedActivé72017-12-04 10:50:47  Voir le détail');
        })
        .then(function () {

            this.echo("Status = SUCCESS & wallet status = NOT IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '1',
                'wallet_status': '4'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '23mirakl_test_23Success Not identifiedActivé232017-12-04 10:51:28  Voir le détail21mirakl_test_21Success Not identifiedActivé212017-12-04 10:51:22  Voir le détail20mirakl_test_20Success Not identifiedActivé202017-12-04 10:51:19  Voir le détail19mirakl_test_19Success Not identifiedActivé192017-12-04 10:51:17  Voir le détail18mirakl_test_18Success Not identifiedActivé182017-12-04 10:51:14  Voir le détail17mirakl_test_17Success Not identifiedActivé172017-12-04 10:51:12  Voir le détail16mirakl_test_16Success Not identifiedActivé162017-12-04 10:51:09  Voir le détail15mirakl_test_15Success Not identifiedActivé152017-12-04 10:51:07  Voir le détail14mirakl_test_14Success Not identifiedActivé142017-12-04 10:51:04  Voir le détail13mirakl_test_13Success Not identifiedActivé132017-12-04 10:51:02  Voir le détail');
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '1'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT CREATED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '2'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail');
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '3'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT IDENTIFIED & empty date ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '4'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, 'Aucun élément à afficher');
        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = 05/12/2017 & end date = 05/12/2017 ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '28mirakl_test_28Critical Afficher messageNot createdActivé282017-12-05 16:18:28  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé262017-12-05 15:55:47  Voir le détail');

        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = 05/12/2017 & end date = EMPTY ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '05/12/2017',
                'end': ''
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '28mirakl_test_28Critical Afficher messageNot createdActivé282017-12-05 16:18:28  Voir le détail27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail26mirakl_test_26Critical Afficher messageNot createdActivé262017-12-05 15:55:47  Voir le détail');

        })
        .then(function () {

            this.echo("Status = EMPTY & wallet status = EMPTY & start date = EMPTY & end date = 04/12/2017 ", "INFO");

            var formInputs = {
                'status': '-1',
                'wallet_status': '-1',
                'start': '',
                'end': '04/12/2017'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '25mirakl_test_25Critical Afficher messageNot createdActivé252017-12-04 15:21:24  Voir le détail24mirakl_test_24Info Not identifiedActivé242017-12-04 10:51:30  Voir le détail23mirakl_test_23Success Not identifiedActivé232017-12-04 10:51:28  Voir le détail22mirakl_test_22Success IdentifiedActivé222017-12-04 10:51:25  Voir le détail21mirakl_test_21Success Not identifiedActivé212017-12-04 10:51:22  Voir le détail20mirakl_test_20Success Not identifiedActivé202017-12-04 10:51:19  Voir le détail19mirakl_test_19Success Not identifiedActivé192017-12-04 10:51:17  Voir le détail18mirakl_test_18Success Not identifiedActivé182017-12-04 10:51:14  Voir le détail17mirakl_test_17Success Not identifiedActivé172017-12-04 10:51:12  Voir le détail16mirakl_test_16Success Not identifiedActivé162017-12-04 10:51:09  Voir le détail');

        })
        .then(function () {

            this.echo("Status = WARNING & wallet status = NOT CREATED & start date = 05/12/2017 & end date = 05/12/2017 ", "INFO");

            var formInputs = {
                'status': '3',
                'wallet_status': '2',
                'start': '05/12/2017',
                'end': '05/12/2017'
            };

            table.checkFilter(test, 'table_vendor', 'form#vendor-filter-form', formInputs, '27mirakl_test_27Warning Afficher messageNot createdActivé272017-12-05 16:16:37  Voir le détail');

        })
        .run(function () {
            test.done();
        });
});
