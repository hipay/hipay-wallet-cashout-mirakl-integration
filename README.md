# HiPay Wallet cash-out integration for Mirakl

[![Build Status](https://circleci.com/gh/hipay/hipay-wallet-cashout-mirakl-integration/tree/master.svg?style=shield)](https://circleci.com/gh/hipay/hipay-wallet-cashout-mirakl-integration/tree/master) [![Code Climate](https://codeclimate.com/github/hipay/hipay-wallet-cashout-mirakl-integration/badges/gpa.svg)](https://codeclimate.com/github/hipay/hipay-wallet-cashout-mirakl-integration) [![Package version](https://img.shields.io/packagist/v/hipay/hipay-wallet-cashout-mirakl-integration.svg)](https://packagist.org/packages/hipay/hipay-wallet-cashout-mirakl-integration) [![GitHub license](https://img.shields.io/badge/license-Apache%202-blue.svg)](https://raw.githubusercontent.com/hipay/hipay-wallet-cashout-mirakl-integration/master/LICENSE.md)

The **HiPay Wallet cash-out integration for Mirakl** is an application based on the [Silex PHP micro-framework][silex], which intends to facilitate cash-out operations between HiPay and the Mirakl marketplace solution.
Please note that this application integrates the [HiPay Wallet cash-out library for Mirakl][repo-lib].

## Getting started

Read the **[project documentation][doc-home]** for comprehensive information about the requirements, general workflow and installation procedure.

## Resources
- [Full project documentation][doc-home] — To have a comprehensive understanding of the workflow and get the installation procedure
- [HiPay Support Center][hipay-help] — To get technical help from HiPay
- [Issues][project-issues] — To report issues, submit pull requests and get involved (see [Apache 2.0 License][project-license])
- [Change log][project-changelog] — To check the changes of the latest versions

## Features

- Automatically creates a HiPay Wallet account for each of your Mirakl merchants
- Automatically retrieves your Mirakl payment operations for paying your Mirakl merchants
- Transfers funds from your technical HiPay Wallet account to your merchants' HiPay Wallet accounts
- Transfers operator's fees from your technical HiPay Wallet account to your operator's HiPay Wallet account
- Leverages the relevant HiPay Wallet API in order to execute withdrawals from HiPay Wallet to both the operator's and merchants' bank accounts

## License

The **HiPay Wallet cash-out integration for Mirakl** is available under the **Apache 2.0 License**. Check out the [license file][project-license] for more information.

[doc-home]: https://developer.hipay.com/doc/hipay-marketplace-cashout-mirakl-integration/

[hipay-help]: http://help.hipay.com

[project-issues]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/issues
[project-license]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
[project-changelog]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/CHANGELOG.md

[silex]: http://silex.sensiolabs.org/
[repo-lib]: https://github.com/hipay/hipay-wallet-cashout-mirakl-library
