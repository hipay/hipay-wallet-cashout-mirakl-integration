#!/usr/bin/env bash

docker exec hipay-mirakl-running php bin/console dbal:import bin/tests/Data/import_log_vendors.sql
docker exec hipay-mirakl-running php bin/console dbal:import bin/tests/Data/import_log_operations.sql
docker exec hipay-mirakl-running php bin/console dbal:import bin/tests/Data/import_logs.sql
