<?php
namespace Hipay\SilexIntegration\Configuration;

use Hipay\MiraklConnector\Api\Mirakl\ConfigurationInterface;
/**
 * File MirkalConfiguration.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class MiraklConfiguration  extends AbstractConfiguration implements ConfigurationInterface
{

    /**
     * Returns the front api key
     *
     * @return string
     */
    public function getFrontKey()
    {
        return parent::$parameters['mirakl.frontKey'];
    }

    /**
     * Return the shop api key
     *
     * @return string
     */
    public function getShopKey()
    {
        return parent::$parameters['mirakl.shopKey'];
    }

    /**
     * Return the operator api key
     *
     * @return string
     */
    public function getOperatorKey()
    {
        return parent::$parameters['mirakl.operatorKey'];
    }

    /**
     * Returns the base url who serve to construct the call
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return parent::$parameters['mirakl.baseUrl'];
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