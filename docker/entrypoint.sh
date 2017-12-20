#!/bin/sh -e

COLOR_SUCCESS='\033[0;32m'
NC='\033[0m'

/var/www/html/docker/scripts/ci.sh

/var/www/html/docker/scripts/setup.sh

#chown www-data:www-data -Rf /var/www/html/

#chmod 755 -Rf /var/www/html/

chmod 777 -Rf /var/www/html/var

touch /var/log/hipay.log

chmod 777 /var/log/hipay.log

exec apache2-foreground
