#!/bin/sh

# Install composer
curl -sS https://getcomposer.org/installer | php -- --filename=composer -- --install-dir=/usr/local/bin

# Get environment parameters file
cp docker/config/parameters.yml config/parameters.yml

# Install packages
composer install --no-interaction

# Create schema
php bin/console orm:schema-tool:update --dump-sql --force
