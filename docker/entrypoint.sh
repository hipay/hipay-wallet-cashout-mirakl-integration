#!/bin/sh -e

COLOR_SUCCESS='\033[0;32m'
NC='\033[0m'

/tmp/scripts/ci.sh

/tmp/scripts/setup.sh

chmod 777 -Rf /var/www/html/var

exec apache2-foreground