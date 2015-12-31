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

class FtpConfiguration extends AbstractConfiguration implements ConfigurationInterface
{

    public function getHost()
    {
        return parent::$parameters['ftp.host'];
    }

    public function getPort()
    {
        return parent::$parameters['ftp.port'];
    }

    public function getUsername()
    {
        return parent::$parameters['ftp.username'];
    }

    public function getPassword()
    {
        return parent::$parameters['ftp.password'];
    }

    public function getConnectionType()
    {
        return parent::$parameters['ftp.connectionType'];
    }
}