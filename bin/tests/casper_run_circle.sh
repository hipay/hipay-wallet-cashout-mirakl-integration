#!/bin/bash

cd bin/tests/000_lib
bower install hipay-casperjs-lib#develop --allow-root
cd ../../../;

header="bin/tests/"


echo "Execute casperjs tests"
casperjs test ${header}000*/*/*/*.js ${header}000*/000[0-2]*.js ${header}0[0-1][0-9]*/[0-1]*/[0-9][0-3][0-9][0-9]-*.js --url=$BASE_URL/ --login-backend=$MIRAKL_CONNECTOR_HIPAY_WS_LOGIN --pass-backend=$MIRAKL_CONNECTOR_HIPAY_WS_PASSWORD --ignore-ssl-errors=true --ssl-protocol=any
