<?php
/**
 * File FtpConfiguration.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

namespace Hipay\SilexIntegration\Configuration;

use Hipay\MiraklConnector\Service\Ftp\ConfigurationInterface;

class FtpConfiguration implements ConfigurationInterface
{

    public function getHost()
    {
        return ParameterAccessor::getParameter('ftp.host');
    }

    public function getPort()
    {
        return ParameterAccessor::getParameter('ftp.port');
    }

    public function getUsername()
    {
        return ParameterAccessor::getParameter('ftp.username');
    }

    public function getPassword()
    {
        return ParameterAccessor::getParameter('ftp.password');
    }

    public function getConnectionType()
    {
        return ParameterAccessor::getParameter('ftp.connectionType');
    }
}