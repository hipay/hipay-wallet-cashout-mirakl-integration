#!/bin/sh -e

COLOR_SUCCESS='\033[0;32m'
NC='\033[0m'

/tmp/scripts/ci.sh

/tmp/scripts/setup.sh

exec apache2-foreground