# Version 2.1.1

- Fixes issue with operations in error (i.e., due to to unidentified accounts) not being processed when the operations were 24 hours old. The correct behavior is to retry the operation processing once per day. [Pull request here](https://github.com/hipay/hipay-wallet-cashout-mirakl-integration/pull/4).

# Version 2.1.0

- This version adds a parameter allowing you to filter shops during the cash-out initialization process.

- Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.1.0. [More information here](https://github.com/hipay/hipay-wallet-cashout-mirakl-library/releases/tag/2.1.0).

# Version 2.0.3
Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.0.3. [More information here](https://github.com/hipay/hipay-wallet-cashout-mirakl-library/releases/tag/2.0.3).

# Version 2.0.2
Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.0.2. [More information here](https://github.com/hipay/hipay-wallet-cashout-mirakl-library/releases/tag/2.0.2).

# Version 2.0.1
Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.0.2. [More information here](https://github.com/hipay/hipay-wallet-cashout-mirakl-library/releases/tag/2.0.1).

# Version 2.0.0
- Leverages the new version of the HiPay Wallet cash-out library for Mirakl which handles KYC documents upload through HiPay Wallet REST API.
- Removes Mirakl shopKey parameter
- Removes FTP configuration (not needed anymore as documents are transferred through HiPay Wallet REST API)

# Version 1.0.6
Fix mistakes in README.

# Version 1.0.5
Add Code Climate badge.

# Version 1.0.4
Read me update.

# Version 1.0.2
Update *HiPay Wallet cash-out library for Mirakl* repository URL.

# Version 1.0.1
Minor bug fixes.

# Version 1.0.0
First production-ready version.