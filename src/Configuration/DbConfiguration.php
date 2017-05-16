<?php
namespace HiPay\Wallet\Mirakl\Integration\Configuration;

/**
 * Class DbConfiguration
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */
class DbConfiguration extends AbstractConfiguration
{
    public function getHost()
    {
        return $this->parameters['db.host'];
    }

    public function getPort()
    {
        return $this->parameters['db.port'];
    }

    public function getUsername()
    {
        return $this->parameters['db.username'];
    }

    public function getPassword()
    {
        return $this->parameters['db.password'];
    }

    public function getDatabaseName()
    {
        return $this->parameters['db.name'];
    }

    public function getDriver()
    {
        return $this->parameters['db.driver'];
    }
}