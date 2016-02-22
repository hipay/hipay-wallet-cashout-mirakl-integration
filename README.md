HiPay-Mirakl Cashout Silex integration
======================================

This is an example integration of the HiPay-Mirakl Cashout Connector.
This was made for a quick and easy set up of the cashout operations
without the need for a developer to intervene (or reducing this intervention at a minimum)

# Prerequisite and recommendations

## General
- The `config/parameters.yml` file must be a YAML file with one top key named “parameters”.
A default example can be seen in the `config/parameters.yml.dist` file.
-The parameters.yml file should not be committed or transmitted to any third party.
- For more information about the inner workings, do not hesitate to see the repository of the library

## System
- You must have at least PHP 5.3.9. The library has dependencies who need PHP at least at this version.
- You should have and use composer. It is the best way to install the library or the integration.
- You should use MySQL. Even though the integration should function with most RDBMSs (thanks to Doctrine),
 it was only tested with MySQL 5.5.
- A web server is required, with a URL accessible by HiPay
with the HTTP verb POST so that server-to-server notifications can be sent by HiPay.
It can be Apache, Nginx or any other choice, as long as the other mandatory requirements are met.

## HiPay
- You must have a technical account before starting.
This is the wallet receiving all the money coming from the customer orders and subsequent payments.
- You must create the operator wallet beforehand.
You need a email who will not be used for another shop of the marketplace.
This email is should be the professional email of the marketplace operator.
For this you will need assistance from HiPay.
- A good understanding of APIs for cash-out transactions may be useful:
please refer to the HiPay Marketplace - APIs overview guide.
- Some operations require HiPay’s assistance: please contact HiPay’s
Business IT Services for technical support at [http://help.hipay.com](http://help.hipay.com).
- You have to provide the FTP. You have to give the connection information to HiPay for them to retrieve the documents

## Mirakl
- Even though Mirakl do not enforce the filling of the phone number, it must be completed to create a HiPay Wallet.
- Even though Mirakl only enforce the filling of the IBAN and the BIC in the banking information section, all form fields must be completed to add the banking information to HiPay.
- You should use only alphanumeric characters for the fields especially for banking information. This is an HiPay limitation as they only accept this category of characters.
- The version of the API should be at least 3. I cannot warrant the behavior for an earlier version.
- Should the API version be higher, as long as the concerned API called remain retro compatible, there should not be any problem.

# Database tables

Two tables need to be created. Should you follow the instruction in the Installation section, below are the changes in the database :

## Vendors

| Field    | Type         | Null | Key | Default | Extra          |
|----------|--------------|------|-----|---------|----------------|
| id       | int(11)      | NO   | PRI | NULL    | auto_increment |
| miraklId | int(11)      | NO   | UNI | NULL    |                |
| email    | varchar(255) | NO   | UNI | NULL    |                |
| hipayId  | int(11)      | NO   | UNI | NULL    |                |

## Operations

| Field          | Type         | Null | Key | Default | Extra          |
|----------------|--------------|------|-----|---------|----------------|
| id             | int(11)      | NO   | PRI | NULL    | auto_increment |
| miraklId       | int(11)      | YES  |     | NULL    |                |
| hipayId        | int(11)      | YES  |     | NULL    |                |
| amount         | double       | NO   |     | NULL    |                |
| cycleDate      | datetime     | NO   |     | NULL    |                |
| withdrawId     | varchar(255) | YES  | UNI | NULL    |                |
| transferId     | varchar(255) | YES  | UNI | NULL    |                |
| status         | int(11)      | NO   |     | NULL    |                |
| updatedAt      | datetime     | NO   |     | NULL    |                |
| paymentVoucher | varchar(255) | NO   |     | NULL    |                |

# Installation
Before starting the installation, please read all instructions.
All relative paths are relative to the root of the installation.

1. If you want to use a separate database for the data of the cash-out operations, create one in your RDBMS.
In any case, note the name of the database you want to use.
2. You need Composer to at least download the dependencies. If you don't already have it go [this page](https://getcomposer.org/download/)
to download it.
3. Deploy the application into your web server.
It can be standard web application folder like `/srv` or `/var/www` but it can also be anywhere you want in the web server.
In all cases,
Move to that folder and run :


    $ cd /installation/folder
    $ git clone https://github.com/hipay/hipay-wallet-cashout-mirakl-integration.git .
    $ mv /path/to/composer.phar .
    $ php composer.phar install


3. During the Composer installation (the last command), a prompt will ask you some answers
(thanks to incenteev/composer-parameter-handler library).
These answers will generate the `config/parameters.yml` file. This file contains all parameters need
for the application to function. Most of them are mandatory.
For more information, please go the parameters section.

4. Configure your web server so that HiPay can access the 'web/index.php'.
This configuration is beyond the scope of the present guide.

5. Note the URL from which the 'web/index.php' is accessible and contact HiPay’s Business IT Services
to configure your HiPay Wallet account.

6. Add the execution rights to the 'bin/console' file,
which is the entry point for the console functions of the application.
This is not mandatory, but advised to do so. Otherwise, you will have to use the PHP executable to run the console.

7. Run the following command:


    $ bin/console orm:schema-tool:update ––dump-sql ––force

This command will initialize the database with the correct tables.

8. To verify that your installation is correct, please go to the URL you sent to HiPay.
A white page with “Hello world” should be displayed.

# Usage

This program is intended to be used with a cron, but can be used directly from the command line.
Please note that default values for command line arguments/parameters are defined in the parameters.yml.

There are 3 main commands:

## Vendor processing


    $ bin/console vendor:process


### Execution
1. Retrieves the vendors from Mirakl.
2. Saves the vendors: creates the HiPay Wallet account, if not already created, or gets the HiPay Wallet account number from HiPay.
3. Validates them.
4. Transfers the KYC files from Mirakl to the FTP set in the configuration.
There is no check for the presence of the files before the transfer, if a file already exist in the ftp with the same name, it will be erased.
Therefore, you should empty this FTP every now and then. If there is a problem, the command will halt its execution.
5. Adds the bank information on the HiPay Wallet account if not already done or checks HiPay’s bank information against the data from Mirakl to make sure they match.
If these are not the same, an error notification is sent.

### Argument
|Name       |Type | Required | Description                           |
|-----------|-----|----------|---------------------------------------|
|lastUpdate |Date |✗         |Date of the last update of Mirakl shops

### Option

|Name       |Type     | Description                           |
|-----------|---------|---------------------------------------|
|zipPath    |String   |Path where the zip containing the documents should be saved
|ftpPath    |String   |Path on the FTP where the KYC files should be transferred

### Cron exemple
This command should be played often (as often as the vendor update their data)

0 0 */7 * * path/to/bin/console vendor:process \`date +%Y-%m-%d\`

## Cashout generate


    $ bin/console cashout:generate


Functions:
1. Retrieves “PAYMENT” transactions from Mirakl to get all the payment vouchers of the cycle.
2. Calculates the amount due to the vendors and the operator thanks to the retrieved payment vouchers.
3. Creates the operations to be executed afterwards, validate them and saves them.

### Argument
|Name       |Type | Required | Description                           |
|-----------|-----|----------|---------------------------------------|
|cycleDate  |Date |✗         |Sets the cycle date                    |

### Option

|Name               |Type       | Description                           |
|-----------        |---------  |---------------------------------------|
|executionDate      |String     |Date to consider for cycle date computing
|intervalBefore     |Interval   |Interval to remove from cycleDate
|intervalAfter      |Interval   |Interval to add to cycleDate

### Cron exemple

This command should be run right after the payment cycle was made at Mirakl

30 0 1,11,21 * * path/to/bin/console vendor:process \`date +%Y-%m-%d\`

## Cashout process


    $ bin/console cashout:process


### Execution
1. Executes, on the HiPay Wallet platform, the transfer of operations in statuses “CREATED” and “TRANSFER_FAILED” dating from at least one day.
2. Executes, on the HiPay Wallet platform, the withdrawal of operations in statuses “TRANSFER_SUCCESS” and “WITHDRAW_FAILED” dating from at least one day.

### Argument
This command doesn’t have any argument.

### Option
This command doesn’t have any option.

### Cron exemple

This command should be run as soon as possible (when you have payments you want to transfer to your sellers)

30 2 1,11,21 * * path/to/bin/console vendor:process \`date +%Y-%m-%d\`

The following commands are not included in the full cashout process but may be useful for debugging :
You need to have debug at true to see something

## Wallet list


    $ bin/console vendor:wallet:list


### Execution
1. Retrieve from HiPay the wallet associated with a merchant group id (associated with an entity)
2. Display them in a table


### Argument
|Name       |Type | Required | Description                           |
|-----------|-----|----------|---------------------------------------|
|merchantGroupId  |Integer |✗         |a merchant group id for fetching the wallets                   |

### Option
This command doesn’t have any option.


## Bank information

### Execution
1. Retrive from HiPay the bank information status
2. If that status is validated, display the information stored by HiPay

### Argument
|Name       |Type | Required | Description                           |
|-----------|-----|----------|---------------------------------------|
|hipayId  |Integer |✓         |The wallet id from which we fetch the bank information              |

### Option
This command doesn’t have any option.

# Parameters

The following table describes the data in parameters.yml. Of course, the user you run the command with should have write access to all the paths you use in this file.

| Name                	        | Type    	                    | Required 	  | Default value 	| Description
|---------------------	        |:---------:                    |:----------: |---------------	|--------------------------------------------
| Mirakl
| `mirakl.frontKey`     	    |         	                    | ✓           |               	                                    | Mirakl API Shop key.Provided by Mirakl
| `mirakl.shopKey`      	    | String	                    | ✓        	  |               	                                    | Mirakl API Shop key.Provided by Mirakl
| `mirakl.operatorKey` 	        | String  	                    | ✓           |               	                                    | Mirakl API Operator key.Provided by Mirakl
| Mirakl cycles                 |       	                    |          	  |               	                                    |
| `cycle.days`                  | Array 	                    | ✓           |[1, 11, 21]                                          | Array of days (1 to 31) on which the cycles is run in the month
| `cycle.hour`                  | Integer	                    | ✓           |0               	                                    | Hour on which the cycle is run
| `cycle.minute`                | Integer	                    | ✓           |0               	                                    | Minute on which the cycle is run
| `cycle.interval.before`       | String	                    | ✓        	  |12 hours               	                            | Time to retire from the cycle time to form an interval from which we will fetch the transaction from Mirakl
| `cycle.interval.after`        | String	                    | ✓        	  |12 hours               	                            | Time to retire from the cycle time to form an interval from which we will fetch the transaction from Mirakl
| HiPay                         | 	                            |          	  |               	                                    |
| `hipay.wsLogin`               | String	                    | ✓           |               	                                    | HiPay web service login key for technical account
| `hipay.wsPassword`            | String	                    | ✓           |               	                                    | HiPay web service password key for technical account
| `hipay.baseUrl`               | String	                    | ✓           |               	                                    | Base URL to HiPay web service
| `hipay.entity`                | String	                    | ✓           |               	                                    | Provided by HiPay
| `hipay.locale`                | String	                    | ✓           |fr_FR               	                                | Locale of created accounts. Format should be: languageCode_ISO3166-1 alpha-2 country code
| `hipay.timezone`              | String	                    | ✓           |Europe/Paris               	                        | Time zone of created accounts. Please see [the php manual](http://php.net/manual/en/timezones.php) for a list of time zones
| `hipay.merchantGroupId`       | Integer	                    | ✗           |               	                                    | Provided by HiPay
| HiPay accounts                | 	                            |          	  |               	                                    |
| `account.technical.email`     | String	                    | ✓           |               	                                    | Technical HiPay Wallet  account email
| `account.technical.hipayId`   | Integer	                    | ✓           |               	                                    | Technical HiPay Wallet  ID
| `account.operator.email`      | String	                    | ✓           |               	                                    | Operator  HiPay Wallet  email
| `account.operator.hipayId`    | Integer	                    | ✓           |               	                                    | Operator  HiPay wallet  ID
| HiPay labels
| `label.public`                | String	                    | ✓           |Public {{miraklId}} – {{hipayId}} - {{cycleDate}}    | Public label for HiPay transfers. Please see hereafter for more details.
| `label.private`               | String	                    | ✓           |Private {{miraklId}} – {{hipayId}} - {{cycleDate}}   | Private label for HiPay transfers. Please see hereafter for more details.
| `label.withdraw`              | String	                    | ✓           |Withdraw {{miraklId}} – {{hipayId}} - {{cycleDate}}  | Withdrawal label for HiPay withdraw. Please see hereafter for more details.
| FTP
| `ftp.host`                    | String	                    | ✓           |               	                                    | FTP hostname
| `ftp.username`                | String	                    | ✓           |               	                                    | FTP username
| `ftp.password`                | String	                    | ✓           |               	                                    | FTP password
| `ftp.port`                    | Integer	                    | ✓           |21               	                                | FTP port
| `ftp.connectionType`          | String	                    | ✓           |ftp               	                                | FTP connection type. Expects ftp, sftp, ftps or local
| `ftp.timeout`                 | Integer	                    | ✓           |90               	                                | FTP timeout
| `ftp.passive`                 | Boolean	                    | ✓           |false               	                                | Enables FTP passive mode
| Database
| `db.driver`                   | String	                    | ✓           |pdo_mysql               	                            | PHP PDO driver. Please refer to [the php manual](http://php.net/manual/en/pdo.drivers.php) for a list of PDO drivers
| `db.host`                     | String	                    | ✓           |               	                                    | DB hostname
| `db.username`                 | String	                    | ✓           |               	                                    | DB username
| `db.password`                 | String	                    | ✓           |               	                                    | DB password
| `db.name`                     | String	                    | ✓           |               	                                    | DB name
| `db.port`                     | Integer	                    | ✓           |               	                                    | DB port
| Mail
| `mail.host`                   | String	                    | ✓           |               	                                    | SMTP server hostname
| `mail.port`                   | Integer	                    | ✓           |               	                                    | SMTP server port
| `mail.security`               | String	                    | ✓           |               	                                    | SMTP server security
| `mail.username`               | String	                    | ✓           |               	                                    | SMTP server username
| `mail.password`               | String	                    | ✓           |               	                                    | SMTP server password
| `mail.subject`                | String	                    | ✓           |               	                                    | Mail subject
| `mail.to`                     | String or Array of string	    | ✓           |               	                                    | Mail recipients for various notifications (notably error notifications)
| `mail.from`                   | String or Array of string	    | ✓           |               	                                    | Mail senders
| Miscellaneous
| `debug`                       | Boolean	                    | ✓           |false               	                                | Activates Debug mode
| `default.zip.path`            | String	                    | ✓           |/tmp/documents.zip               	                | Default path to save the downloaded zip file
| `default.ftp.path`            | String	                    | ✓           |/               	                                    | Default path to the folder containing the shops’ documents on the remote server
| `log.file.path`               | String	                    | ✓           |/var/log/hipay.log               	                | Path to the log file

Labels give a description of the transaction (transfer or withdraw) and appear on the account statement.
For label parameters (label.public, label.private, label.withdraw), the following placeholder strings can be used, which will be replaced before sending the data to HiPay.
Each string must be surrounded by {{ and }} to be replaced.

| Placeholder     | Description                                                             |
|-----------------|-------------------------------------------------------------------------|
| `miraklId`      | Shop ID if it exists, or operator ID in case of an operator’s operation |
| `amount`        | Amount of the operation                                                 |
| `hipayId`       | HiPay Wallet account ID                                                 |
| `cycleDate`     | Cycle date of the operation                                             |
| `cycleDateTime` | Cycle date and time of the operation                                    |
| `cycleTime`     | Cycle time of the operation                                             |
| `date`          | Execution date of the operation                                         |
| `dateTime`      | Execution date and time of the operation                                |
| `time`          | Execution time of the operation                                         |



