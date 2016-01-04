<?php
/**
 * File HipayConfiguration.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

namespace Hipay\SilexIntegration\Configuration;

use Hipay\MiraklConnector\Api\Hipay\ConfigurationInterface;

class HipayConfiguration implements ConfigurationInterface
{

    /**
     * Returns the web service login given by HiPay
     * @return string
     */
    public function getWebServiceLogin()
    {
        return ParameterAccessor::getParameter('hipay.wsLogin');
    }

    /**
     * Returns the web service password given by HiPay
     * @return string
     */
    public function getWebServicePassword()
    {
        return ParameterAccessor::getParameter('hipay.wsPassword');
    }

    /**
     * Returns the base url who serve to construct the call
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return ParameterAccessor::getParameter('hipay.baseUrl');
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