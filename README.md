# HiPay Wallet cash-out integration for Mirakl

[![Build Status](https://circleci.com/gh/hipay/hipay-wallet-cashout-mirakl-integration/tree/master.svg?style=shield)](https://circleci.com/gh/hipay/hipay-wallet-cashout-mirakl-integration/tree/master)

The **HiPay Wallet cash-out integration for Mirakl** is an application based on the [Silex PHP micro-framework][silex] which intends to facilitate cash-out operations between HiPay and the Mirakl marketplace solution.
Note that this application integrates the [HiPay Wallet cash-out library for Mirakl][repo-lib].

## Getting Started

Read the **[project documentation][doc-home]** for comprehensive information about the requirements, general workflow and installation procedure.

## Resources
- [Full project documentation][doc-home] — For a comprehensive understanding of the workflow and getting the installation procedure
- [HiPay Support Center][hipay-help] — If you need any technical help from HiPay
- [Issues][project-issues] — Report issues, submit pull requests, and get involved (see [Apache 2.0 License][project-license])
- [Change log][project-changelog] — If you want to check the changes of the latest versions

## Features

- Automatically creates a HiPay Wallet account for each of your Mirakl merchant
- Automatically retrieves your Mirakl payment operations for paying your Mirakl merchants
- Transfer funds from your technical HiPay Wallet account to your merchants' HiPay Wallet ones
- Transfer operator's fees from your technical HiPay Wallet account to your operator's HiPay Wallet account
- Leverages the HiPay Wallet API in order to execute withdrawals from HiPay Wallet to both the operator's and merchants' bank accounts

## License

The **HiPay Wallet cash-out integration for Mirakl** is available under the **Apache 2.0 License**. Check out the [license file][project-license] for more information.

[doc-home]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/wiki

[hipay-help]: http://help.hipay.com

[project-issues]: https://github.com/hipay/hipay-wallet-cashout-mirakl/issues
[project-license]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/LICENSE.md
[project-changelog]: https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/blob/master/CHANGELOG.md

[silex]: http://silex.sensiolabs.org/
[repo-lib]: https://github.com/hipay/hipay-wallet-cashout-mirakl-library