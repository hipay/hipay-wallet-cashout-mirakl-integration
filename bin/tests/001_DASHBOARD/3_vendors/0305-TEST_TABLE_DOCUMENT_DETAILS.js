casper.test.begin('Test vendor logs documents details link', function (test) {
    phantom.clearCookies();

    casper.start(baseURL)
        .then(function () {
            authentification.proceed(test, admin_login, admin_passwd);
        })
        .then(function () {
            table.checkFirstPage(test, 'table_vendor');
        })
        .then(function () {
            table.checkDocumentsDetails(test, 'table_vendor', '2019', 'IDproofRefusedMissinginformationCompanyregistrationValidated-DistributionofpowerValidated-BankValidated-');
        })
        .run(function () {
            test.done();
        });
});
