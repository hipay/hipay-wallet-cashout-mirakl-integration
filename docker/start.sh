#!/bin/bash
while !((ping -c1 -W1 db) && (ping -c1 -W1 ftp) && (ping -c1 -W1 smtp) && (ping -c1 -W1 getcomposer.org)); do sleep 3; done

# Install composer
curl -sS https://getcomposer.org/installer | php -- --filename=composer -- --install-dir=/usr/local/bin

# Get environment parameters file
cp docker/config/parameters.yml config/parameters.yml

# Install packages
composer install --no-interaction

# Create schema
php bin/console orm:schema-tool:update --dump-sql --force
