<?php
/**
 * File AbstractConfiguration.php
 *
 * @category
 * @package
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

namespace Hipay\SilexIntegration\Configuration;


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