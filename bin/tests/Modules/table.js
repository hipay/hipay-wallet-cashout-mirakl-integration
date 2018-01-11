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
 * Datatable functions
 */

/**
 * Change page, Datatable pagination
 * @param test
 * @param tableId
 * @param nbRows
 * @param page
 */
exports.changePage = function changePage(test, tableId, nbRows, page) {
    casper.then(function () {

        this.echo("Changing page...", "INFO");

        var oldPageText = this.fetchText('#' + tableId + ' > tbody > tr > td:first-child');

        this.click('#' + tableId + '_next > a');

        this.waitWhileVisible('#' + tableId + '_processing', function success() {
            var currentPageText = this.fetchText('#' + tableId + ' > tbody > tr > td:first-child');
            test.assertElementCount('#' + tableId + ' > tbody > tr', nbRows, page + ' page of table is loaded');
            test.assertNotEquals(oldPageText, currentPageText, page + ' page is different from previous');
        }, function fail() {
            test.assertElementCount('#' + tableId + ' > tbody > tr', nbRows, page + ' page of table not loaded');
        }, 10000);
    });
};

/**
 * Check if there's 10 rows on first page of the table
 * @param test
 * @param tableId
 */
exports.checkFirstPage = function checkFirstPage(test, tableId) {
    casper.then(function () {

        this.echo("Checking first page...", "INFO");

        this.waitForSelector('#' + tableId + ' > tbody > tr', function success() {
            test.assertElementCount('#' + tableId + ' > tbody > tr', 10, 'first page of table loaded');
        }, function fail() {
            test.assertElementCount('#' + tableId + ' > tbody > tr', 10, 'first page of table not loaded');
        }, 10000);
    });
};

/**
 * Check if there is the correct number of pagination page
 * @param test
 * @param page
 * @param nbPage
 */
exports.checkPaginationButton = function checkPaginationButton(test, page, nbPage) {
    casper.then(function () {
        this.echo("Checking pagination button exist ...", "INFO");

        test.assertElementCount('.paginate_button', nbPage + 2, 'All ' + page + ' are loaded');
    });
};

/**
 * Check if error buttons exists
 * @param test
 * @param tableId
 * @param buttonClass
 */
exports.errorButtonExist = function errorButtonExist(test, tableId, buttonClass) {
    casper.then(function () {
        this.echo("Checking error message button exist ...", "INFO");

        test.assertExists('#' + tableId + ' tbody tr:first-child .' + buttonClass, 'Error message button exist');
    });
};

/**
 * Check if error message exists when we click on error button
 * @param test
 * @param tableId
 * @param buttonClass
 */
exports.checkErrorMessage = function checkErrorMessage(test, tableId, buttonClass) {
    casper.then(function () {
        this.echo("Checking error message is displayed ...", "INFO");

        this.click('#' + tableId + ' tbody tr:first-child .' + buttonClass);
        this.waitForSelector('.popover', function success() {
            var text = this.fetchText('.popover-content');
            test.assertNotEquals(text, "", "Error message is displayed");
        }, function fail() {
            test.assertExists('.popover', 'Error message is not displayed');
        }, 10000);
    });
};

/**
 * Check if datatable sorting columns works
 * @param test
 * @param tableId
 * @param column
 * @param id
 * @param expectedValue
 */
exports.checkSortColumn = function checkSortColumn(test, tableId, column, id, expectedValue) {
    casper.then(function () {
        this.echo("Checking sorting column ... " + column, "INFO");

        this.click('#' + tableId + ' thead tr:first-child th:nth-child(' + id + ')');

        this.waitWhileVisible('#' + tableId + '_processing', function success() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(' + id + ')');
            test.assertEquals(value, expectedValue, "Sorting working for column " + column);
        }, function fail() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(' + id + ')');
            test.assertEquals(value, expectedValue, "Sorting working for column " + column);
        }, 10000);
    });
};

/**
 * Checking datatable search
 * @param test
 * @param tableId
 * @param searchText
 * @param expectedValue
 * @param idColumnCheck
 */
exports.checkSearch = function checkSearch(test, tableId, searchText, expectedValue, idColumnCheck) {
    casper.then(function () {
        this.echo("Checking search ... " + searchText, "INFO");

        this.sendKeys('#' + tableId + '_filter > label > input', searchText, {reset: true, keepFocus: true});

        this.echo("WAIT !", "INFO");

        this.wait(500, function () {
            this.echo('Was just waiting to fix little search test bug', "INFO")
        });

        this.waitWhileVisible('#' + tableId + '_processing', function success() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(' + idColumnCheck + ')');
            test.assertEquals(value, expectedValue, "Search working ");
        }, function fail() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(' + idColumnCheck + ')');
            test.assertEquals(value, expectedValue, "Search working ");
        }, 10000);
    });
};

/**
 * Check if filter form works
 * @param test
 * @param tableId
 * @param formId
 * @param formInputs
 * @param expectedValue
 */
exports.checkFilter = function checkFilter(test, tableId, formId, formInputs, expectedValue) {
    casper.then(function () {
        this.echo("Checking filter ... ", "INFO");

        this.fill(formId, formInputs);

        this.click("#filter-action");

        this.waitWhileVisible('#' + tableId + '_processing', function success() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr');
            test.assertEquals(value, expectedValue, "Filters working ");
        }, function fail() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr');
            test.assertEquals(value, expectedValue, "Filters working ");
        }, 10000);
    });
};

/**
 * Check if documents details links loads the right data
 * @param test
 * @param tableId
 * @param miraklId
 * @param expectedValue
 */
exports.checkDocumentsDetails = function checkDocumentsDetails(test, tableId, miraklId, expectedValue) {
    casper.then(function () {
        this.echo("Checking documents details link ... " + miraklId, "INFO");

        this.sendKeys('#' + tableId + '_filter > label > input', miraklId, {reset: true, keepFocus: true});

        this.echo("WAIT !", "INFO");

        this.wait(500, function () {
            this.echo('Was just waiting to fix little search test bug', "INFO")
        });

        this.waitWhileVisible('#' + tableId + '_processing', function success() {

            //test.assertEquals(value, miraklId, "Search working ");
            this.click('#' + tableId + ' > tbody > tr > td:nth-child(8) > a');

            this.waitWhileSelector('#loader-document-page', function success() {
                var value = this.fetchText('#documents-page .table > tbody > tr').replace(/\s/g, '');
                test.assertEquals(value, expectedValue, "Documents details link working ");
            }, function fail() {
                var value = this.fetchText('#documents-page > tbody > tr').replace(/\s/g, '');
                test.assertEquals(value, expectedValue, "Documents details link working ");
            }, 10000);

        }, function fail() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(1)');
            test.assertEquals(value, miraklId, "Search working ");
        }, 10000);
    });
};

/**
 * Check if export button works
 * @param test
 * @param fileName
 * @param buttonClass
 * @param expectedFileContent
 */
exports.checkDownloadFile = function checkDownloadFile(test, fileName, buttonClass, expectedFileContent) {
    casper.then(function () {

        this.echo("Checking download file from export button ...", "INFO");

        var listener = function (resource) {
            if (resource.stage !== 'end') {
                return;
            }
            if (resource.url.indexOf(fileName) > -1) {
                test.assertNotEquals(resource.url.indexOf(fileName), -1, "link downloading the correct file");
                this.download(resource.url, fileName);
                var data = fs.read(fileName);
                test.assertEquals(data, expectedFileContent, "file contains correct content");
            } else {
                this.download(resource.url, pathErrors + fileName);
                test.assertNotEquals(resource.url.indexOf(fileName), -1, "link downloading incorrect file");
            }


        };

        this.echo('Adding custom listener on resource.received', "INFO")
        casper.on('resource.received', listener);

        this.click(buttonClass);
        // remove custom listener after tests
        this.wait(500, function () {
            this.echo('File has been tested, removing listener on resource.received', "INFO")
            this.removeListener("resource.received", listener);
        });
    });
};
