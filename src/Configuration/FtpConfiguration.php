<?php
namespace HiPay\Wallet\Mirakl\Integration\Configuration;

use HiPay\Wallet\Mirakl\Service\Ftp\ConfigurationInterface;

class FtpConfiguration extends AbstractConfiguration implements ConfigurationInterface
{

    public function getHost()
    {
        return $this->parameters['ftp.host'];
    }

    public function getPort()
    {
        return $this->parameters['ftp.port'];
    }

    public function getUsername()
    {
        return $this->parameters['ftp.username'];
    }

    public function getPassword()
    {
        return $this->parameters['ftp.password'];
    }

    public function getConnectionType()
    {
        return $this->parameters['ftp.connectionType'];
    }

    /**
     * Returns the ftp timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->parameters['ftp.timeout'];
    }

    /**
     * Return the true if connection is passive, false otherwise
     *
     * @return boolean
     */
    public function isPassive()
    {
        return $this->parameters['ftp.passive'];
    }
}