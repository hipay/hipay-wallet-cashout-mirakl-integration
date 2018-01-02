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

exports.checkPaginationButton = function checkPaginationButton(test, page, nbPage) {
    casper.then(function () {
        this.echo("Checking pagination button exist ...", "INFO");

        test.assertElementCount('.paginate_button', nbPage + 2, 'All ' + page + ' are loaded');
    });
};

exports.errorButtonExist = function errorButtonExist(test, tableId, buttonClass) {
    casper.then(function () {
        this.echo("Checking error message button exist ...", "INFO");

        test.assertExists('#' + tableId + ' tbody tr:first-child .' + buttonClass, 'Error message button exist');
    });
};

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
                this.capture('0305-TEST_TABLE_DOCUMENT_DETAILS.png');
                test.assertEquals(value, expectedValue, "Documents details link working ");
            }, 10000);

        }, function fail() {
            var value = this.fetchText('#' + tableId + ' > tbody > tr > td:nth-child(1)');
            test.assertEquals(value, miraklId, "Search working ");
        }, 10000);
    });
};
