#!/usr/bin/env bash
cd /var/www/html
php bin/console dbal:import bin/tests/Data/import_log_vendors.sql
php bin/console dbal:import bin/tests/Data/import_log_operations.sql
php bin/console dbal:import bin/tests/Data/import_logs.sql
exit
