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
class MiraklConfiguration implements ConfigurationInterface
{

    /**
     * Returns the front api key
     *
     * @return string
     */
    public function getFrontKey()
    {
        return ParameterAccessor::getParameter('mirakl.frontKey');
    }

    /**
     * Return the shop api key
     *
     * @return string
     */
    public function getShopKey()
    {
        return ParameterAccessor::getParameter('mirakl.shopKey');
    }

    /**
     * Return the operator api key
     *
     * @return string
     */
    public function getOperatorKey()
    {
        return ParameterAccessor::getParameter('mirakl.operatorKey');
    }

    /**
     * Returns the base url who serve to construct the call
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return ParameterAccessor::getParameter('mirakl.baseUrl');
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