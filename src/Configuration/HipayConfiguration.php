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

class HipayConfiguration extends AbstractConfiguration implements ConfigurationInterface
{

    /**
     * Returns the web service login given by HiPay
     * @return string
     */
    public function getWebServiceLogin()
    {
        return $this->parameters['hipay.wsLogin'];
    }

    /**
     * Returns the web service password given by HiPay
     * @return string
     */
    public function getWebServicePassword()
    {
        return $this->parameters['hipay.wsPassword'];
    }

    /**
     * Returns the base url who serve to construct the call
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->parameters['hipay.baseUrl'];
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

    /**
     * Return the entity given to the merchant by Hipay
     * @return string
     */
    public function getEntity()
    {
        return $this->parameters['hipay.entity'];
    }

    /**
     * Returns the locale used in the webservice calls
     */
    public function getLocale()
    {
        return $this->parameters['hipay.locale'];
    }

    /**
     * Returns the timezone used in the webservice calls
     */
    public function getTimezone()
    {
        return $this->parameters['hipay.timezone'];
    }
}