#!/bin/sh -e

header="bin/tests/"
pathPreFile=${header}000*/*.js
pathDir=${header}0*

#=============================================================================
#  Use this script build hipay images and run Hipay containers
#==============================================================================
if [ "$1" = '' ] || [ "$1" = '--help' ];then
    printf "\n                                                                                  "
    printf "\n ================================================================================ "
    printf "\n                                  HiPay'S HELPER                                 "
    printf "\n                                                                                  "
    printf "\n For each commands, you may specify the prestashop version "16" or "17"           "
    printf "\n ================================================================================ "
    printf "\n                                                                                  "
    printf "\n                                                                                  "
    printf "\n      - init      : Build images and run containers (Delete existing volumes)     "
    printf "\n      - restart   : Run all containers if they already exist                      "
    printf "\n      - up        : Up containters                                                "
    printf "\n      - exec      : Bash prestashop.                                              "
    printf "\n      - log       : Log prestashop.                                               "
    printf "\n                                                                                  "
fi

if [ "$1" = 'init' ] && [ "$2" = '' ];then
     docker-compose -f docker-compose.dev.yml  stop
     docker-compose -f docker-compose.dev.yml rm -fv
     rm -Rf composer.lock
     rm -Rf vendor/
     docker-compose -f docker-compose.dev.yml build --no-cache
     docker-compose -f docker-compose.dev.yml up -d
fi

if [ "$1" = 'init-production' ] && [ "$2" = '' ];then
     docker-compose -f docker-compose.yml -f docker-compose.production.yml stop
     docker-compose -f docker-compose.yml -f docker-compose.production.yml rm -fv
     docker-compose -f docker-compose.yml -f docker-compose.production.yml build --no-cache
     docker-compose -f docker-compose.yml -f docker-compose.production.yml up -d
fi

if [ "$1" = 'init-stage' ] && [ "$2" = '' ];then
     docker-compose -f docker-compose.yml -f docker-compose.stage.yml stop
     docker-compose -f docker-compose.yml -f docker-compose.stage.yml rm -fv
     docker-compose -f docker-compose.yml -f docker-compose.stage.yml build --no-cache
     docker-compose -f docker-compose.yml -f docker-compose.stage.yml up -d
fi

if [ "$1" = 'restart' ];then
     docker-compose -f docker-compose.dev.yml  stop
     docker-compose -f docker-compose.dev.yml  up -d
fi

if [ "$1" = 'kill' ];then
     docker-compose -f docker-compose.dev.yml  stop
     docker-compose -f docker-compose.dev.yml rm -fv
     rm -Rf composer.lock
     rm -Rf vendor/
fi

if [ "$1" = 'logs' ];then
     docker-compose -f docker-compose.dev.yml logs -f
fi

if [ "$1" = 'test' ]; then

   rm -rf bin/tests/errors/*
   printf "Errors from previous tests cleared !\n\n"

   if [ "$(ls -A ~/.local/share/Ofi\ Labs/PhantomJS/)" ]; then
       rm -rf ~/.local/share/Ofi\ Labs/PhantomJS/*
       printf "Cache cleared !\n\n"
   else
       printf "Pas de cache à effacer !\n\n"
   fi

   BASE_URL="http://localhost:8080/web/index.php"

   casperjs test $pathPreFile ${pathDir}/[6-9]*/[0-9][0-9][0-9][2-9]-*.js --url=$BASE_URL --ignore-ssl-errors=true --ssl-protocol=any --github-token=$4 --login-backend=$2 --pass-backend=$3
fi
