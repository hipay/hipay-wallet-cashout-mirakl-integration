<?php
namespace HiPay\Wallet\Mirakl\Integration\Configuration;

use ArrayAccess;

abstract class AbstractConfiguration
{
    /** @var array|ArrayAccess config parameters */
    protected $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }
}