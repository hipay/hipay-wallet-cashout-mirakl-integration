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

use Symfony\Component\Yaml\Yaml;

class ParameterAccessor
{
    /**
     * Configuration parameters
     *
     * @var array
     */
    protected static $parameters;

    /**
     * Th
     * @param string $key the key of the parameter to get
     * @return mixed
     */
    public static function getParameter($key) {
        if (!self::$parameters) {
            $yaml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.yml');
            self::$parameters = Yaml::parse($yaml);
        }
        return self::$parameters[$key];
    }

    /**
     * AbstractConfiguration constructor.
     *
     * Parse the yaml config file (located at $root/config/parameter.yml) to extract the parameters
     */
    private function __construct()
    {
        if (!static::$parameters) {
            $yaml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'parameters.yml');
            static::$parameters = Yaml::parse($yaml);
        }
    }
}