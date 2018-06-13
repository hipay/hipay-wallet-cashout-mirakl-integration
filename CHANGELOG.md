# Version 3.3.0
- Add country to vendor data
- Block withdraw for vendor flag as "Payment Bocked" (Mirakl BO)
- Add payment voucher to public and private label (transaction)

# Version 3.2.0
- New - Add functionals tests

# Version 3.1.1
- Handle vendor already database with HIPA_PROCESS = NO

# Version 3.1.0
- Minor graphical UI changes
- Add "github token" in settings

# Version 3.0.1
- Fix refused message not displayed (KYC status)

# Version 3.0.0
- Switch from TL01 Mirakl API to IV01
- Add negative operations adjustment process
- Add application update from GUI
- Code refactoring

# Version 2.6.0
- Add new command to recover logs from past vendors
- Add parameters to call REST api instead of soap
- Add csv export for logs
- Dashboard bug fixes
- Add new cron rerun in parameters screen

# Version 2.5.2
- Fix HipPay library install bug

# Version 2.5.1
- Add continuous deployement
- Fix cashout process bug

# Version 2.5.0
- Add a dashboard to display vendors, operations and general logs
- Add a screen to the dashboard to run batch from UI
- Set up vendors, operations and general logs entities

# Version 2.4.1
- Fix signature with callback_salt
- Fix array address informations
- Fix HTML format send by email
- Fix management Error level to send an alert email

# Version 2.4.0

- The login is no longer the email, it is now registered by the concatenation of the name of the shop and its ID
- Migration to REST API for Bank info management 
- Migration to REST API for account creation
- New management of callback notifications with HTML Structure

# Version 2.3.0

- Send a notification by Email when KYC is invalidated
- New mandatory fields for the bank Informations
- Remove all special characters when the bank informations are sent to Wallet
- Bugfix iso code Bank country 

# Version 2.2.2

Fix Transfers the vat number in the Wallet account creation
Fix Correct routing when URI is not

# Version 2.2.1

Updates links to documentation.

# Version 2.2.0

Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.2.1.

# Version 2.1.2

Updates the HiPay Wallet cash-out library for Mirakl dependency to version 2.1.1. [More information here](https://github.com/hipay/hipay-wallet-cashout-mirakl-library/releases/tag/2.1.1).

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
