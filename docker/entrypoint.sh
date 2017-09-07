#!/bin/sh -e

COLOR_SUCCESS='\033[0;32m'
NC='\033[0m'

/docker/scripts/ci.sh

/docker/scripts/setup.sh

chmod 777 -Rf /var/www/html/var

exec apache2-foreground