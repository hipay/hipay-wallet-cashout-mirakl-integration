#!/bin/sh

cp docker/config/parameters_example.yml docker/config/parameters.yml

echo "
    mirakl.frontKey: ${MIRAKL_CONNECTOR_MIRAKL_FRONT_KEY}
    mirakl.shopKey: ${MIRAKL_CONNECTOR_MIRAKL_SHOP_KEY}
    mirakl.operatorKey: ${MIRAKL_CONNECTOR_MIRAKL_OPERATOR_KEY}
    mirakl.baseUrl: ${MIRAKL_CONNECTOR_MIRAKL_BASE_URL}

    # HiPay info (to be updated)
    hipay.wsLogin: ${MIRAKL_CONNECTOR_HIPAY_WS_LOGIN}
    hipay.wsPassword: ${MIRAKL_CONNECTOR_HIPAY_WS_PASSWORD}
    hipay.baseUrl: ${MIRAKL_CONNECTOR_HIPAY_BASE_URL}
    hipay.entity: ${MIRAKL_CONNECTOR_HIPAY_ENTITY}
    hipay.merchantGroupId: ${MIRAKL_CONNECTOR_HIPAY_MERCHANT_GROUP_ID}

    account.technical.email: ${MIRAKL_CONNECTOR_ACCOUNT_TECHNICAL_EMAIL}
    account.technical.hipayId: ${MIRAKL_CONNECTOR_ACCOUNT_TECHNICAL_HIPAY_ID}
    account.operator.email: ${MIRAKL_CONNECTOR_ACCOUNT_OPERATOR_EMAIL}
    account.operator.hipayId: ${MIRAKL_CONNECTOR_ACCOUNT_OPERATOR_HIPAY_ID}" >> docker/config/parameters.yml