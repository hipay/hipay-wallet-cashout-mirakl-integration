#!/bin/sh -e

COLOR_SUCCESS='\033[0;32m'
NC='\033[0m'

cp bin/config/parameters_example.yml bin/config/parameters.yml

echo "
    mirakl.frontKey: ${MIRAKL_CONNECTOR_MIRAKL_FRONT_KEY}
    mirakl.operatorKey: ${MIRAKL_CONNECTOR_MIRAKL_OPERATOR_KEY}
    mirakl.baseUrl: ${MIRAKL_CONNECTOR_MIRAKL_BASE_URL}

    hipay.wsLogin: ${MIRAKL_CONNECTOR_HIPAY_WS_LOGIN}
    hipay.wsPassword: ${MIRAKL_CONNECTOR_HIPAY_WS_PASSWORD}
    hipay.baseSoapUrl: ${MIRAKL_CONNECTOR_HIPAY_BASE_SOAP_URL}
    hipay.baseRestUrl: ${MIRAKL_CONNECTOR_HIPAY_BASE_REST_URL}
    hipay.transfer.withdraw.rest: true
    hipay.entity: ${MIRAKL_CONNECTOR_HIPAY_ENTITY}
    hipay.merchantGroupId: ${MIRAKL_CONNECTOR_HIPAY_MERCHANT_GROUP_ID}
    hipay.mkp.technical.id: ${MIRAKL_CONNECTOR_HIPAY_MKP_TECH_ID}

    db.host: ${MIRAKL_CONNECTOR_MIRAKL_DB_HOST}

    debug: true

    account.technical.email: ${MIRAKL_CONNECTOR_ACCOUNT_TECHNICAL_EMAIL}
    account.technical.hipayId: ${MIRAKL_CONNECTOR_ACCOUNT_TECHNICAL_HIPAY_ID}
    account.operator.email: ${MIRAKL_CONNECTOR_ACCOUNT_OPERATOR_EMAIL}
    account.operator.hipayId: ${MIRAKL_CONNECTOR_ACCOUNT_OPERATOR_HIPAY_ID}" >> bin/config/parameters.yml

# Install composer
curl -sS https://getcomposer.org/installer | php -- --filename=composer -- --install-dir=/usr/local/bin

if [ "$GITHUB_TOKEN" != "" ];then
    composer config -g github-oauth.github.com ${GITHUB_TOKEN}
fi

# Get environment parameters file
\cp -fR bin/config/parameters.yml config/parameters.yml

# Install packages
composer install --no-interaction

# Create schema
php bin/console orm:schema-tool:update --dump-sql --force

if [ "$ENV" != "dev" ];then
    chown www-data:www-data -Rf /var/www/html/
    chmod 755 -Rf /var/www/html/
else
    # INSTALL X DEBUG
    echo '' | pecl install xdebug-2.6.1

    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini
    echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
fi

echo $ENV

if [ "$ENV" = "test" ];then
  echo "Initialize database data"
  php bin/console dbal:import bin/docker/images/Data/import_log_vendors.sql
  php bin/console dbal:import bin/docker/images/Data/import_vendors.sql
  php bin/console dbal:import bin/docker/images/Data/import_log_operations.sql
  php bin/console dbal:import bin/docker/images/Data/import_logs.sql
else
  echo "No database data Initialisation"
fi

chmod 777 -Rf /var/www/html/var

touch /var/log/hipay.log

chmod 777 /var/log/hipay.log

#===================================#
#       START WEBSERVER
#===================================#
printf "${COLOR_SUCCESS}                                                                           ${NC}\n"
printf "${COLOR_SUCCESS}    |======================================================================${NC}\n"
printf "${COLOR_SUCCESS}    |                                                                      ${NC}\n"
printf "${COLOR_SUCCESS}    |               DOCKER MIRAKL INTEGRATION IS UP                        ${NC}\n"
printf "${COLOR_SUCCESS}    |                                                                      ${NC}\n"
printf "${COLOR_SUCCESS}    |======================================================================${NC}\n"
exec apache2-foreground
