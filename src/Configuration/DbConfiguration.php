<?php
/**
 * File DbConfiguration.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

namespace Hipay\SilexIntegration\Configuration;

/**
 * Class DbConfiguration
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class DbConfiguration
{
    public function getHost()
    {
        return ParameterAccessor::getParameter('db.host');
    }

    public function getPort()
    {
        return ParameterAccessor::getParameter('db.port');
    }

    public function getUsername()
    {
        return ParameterAccessor::getParameter('db.username');
    }

    public function getPassword()
    {
        return ParameterAccessor::getParameter('db.password');
    }

    public function getDatabaseName()
    {
        return ParameterAccessor::getParameter('db.name');
    }

    public function getDebug()
    {
        return ParameterAccessor::getParameter('doctrine.debug');
    }
}