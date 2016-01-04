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


class DbConfiguration extends AbstractConfiguration
{
    public function getHost()
    {
        return parent::$parameters['db.host'];
    }

    public function getPort()
    {
        return parent::$parameters['db.port'];
    }

    public function getUsername()
    {
        return parent::$parameters['db.username'];
    }

    public function getPassword()
    {
        return parent::$parameters['db.password'];
    }

    public function getDatabaseName()
    {
        return parent::$parameters['db.name'];
    }

    public function getDebug()
    {
        return parent::$parameters['doctrine.debug'];
    }
}