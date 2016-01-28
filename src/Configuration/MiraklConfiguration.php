<?php
namespace HiPay\Wallet\Mirakl\Integration\Configuration;

use HiPay\Wallet\Mirakl\Api\Mirakl\ConfigurationInterface;

/**
 * File MirkalConfiguration.php
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class MiraklConfiguration extends AbstractConfiguration implements ConfigurationInterface
{

    /**
     * Returns the front api key
     *
     * @return string
     */
    public function getFrontKey()
    {
        return $this->parameters['mirakl.frontKey'];
    }

    /**
     * Return the shop api key
     *
     * @return string
     */
    public function getShopKey()
    {
        return $this->parameters['mirakl.shopKey'];
    }

    /**
     * Return the operator api key
     *
     * @return string
     */
    public function getOperatorKey()
    {
        return $this->parameters['mirakl.operatorKey'];
    }

    /**
     * Returns the base url who serve to construct the call
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->parameters['mirakl.baseUrl'];
    }

    /**
     * Returns the configuration array
     * compatible with the rest or soap client used
     *
     * @return array
     */
    public function getOptions()
    {
        return array();
    }
}