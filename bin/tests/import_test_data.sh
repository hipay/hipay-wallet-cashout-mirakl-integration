#!/usr/bin/env bash

docker-compose -p $1 -f docker-compose.test.yml exec -T web php bin/console dbal:import bin/tests/Data/import_log_vendors.sql
docker-compose -p $1 -f docker-compose.test.yml exec -T web php bin/console dbal:import bin/tests/Data/import_vendors.sql
docker-compose -p $1 -f docker-compose.test.yml exec -T web php bin/console dbal:import bin/tests/Data/import_log_operations.sql
docker-compose -p $1 -f docker-compose.test.yml exec -T web php bin/console dbal:import bin/tests/Data/import_logs.sql
