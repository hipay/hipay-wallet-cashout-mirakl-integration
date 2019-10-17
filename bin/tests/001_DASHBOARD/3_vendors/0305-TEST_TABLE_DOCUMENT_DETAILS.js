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
 * Test vendor logs documents details link
 */
casper.test.begin('Test vendor logs documents details link', function (test) {
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
            // test if popup displays and if data in table are correct
            table.checkDocumentsDetails(
                test,
                'table_vendor',
                '2019',
                'IdentitycardRefusedMissinginformationsCompanyRegistrationValidated-ArticleofAssociationValidated' +
                '-BankWaitingmanualinputbankdetails-'
            );
        })
        .run(function () {
            test.done();
        });
});
