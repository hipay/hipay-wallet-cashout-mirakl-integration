#!/bin/bash

header="bin/tests/"
pathPreFile=${header}000*/*.js
pathLibHipay=${header}000*/*/*/*.js
pathDir=${header}0*


echo "Execute casperjs tests"
casperjs test $pathPreFile ${pathDir}/[0-1]*/[0-9][0-9][0-9][0-9]-*.js --url=$BASE_URL/ --login-backend=$MIRAKL_CONNECTOR_HIPAY_WS_LOGIN --pass-backend=$MIRAKL_CONNECTOR_HIPAY_WS_PASSWORD --ignore-ssl-errors=true --ssl-protocol=any
