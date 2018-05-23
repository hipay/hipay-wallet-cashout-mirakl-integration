#!/usr/bin/env bash

docker-compose exec -T mirakl php bin/console dbal:import bin/tests/Data/import_log_vendors.sql
docker-compose exec -T mirakl php bin/console dbal:import bin/tests/Data/import_vendors.sql
docker-compose exec -T mirakl php bin/console dbal:import bin/tests/Data/import_log_operations.sql
docker-compose exec -T mirakl php bin/console dbal:import bin/tests/Data/import_logs.sql
