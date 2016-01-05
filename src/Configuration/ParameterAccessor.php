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
            $parameterFilePath = join(DIRECTORY_SEPARATOR, array(
                ROOT_PATH,
                '..',
                'config',
                'parameters.yml'
            ));
            $yaml = file_get_contents($parameterFilePath);
            self::$parameters = Yaml::parse($yaml);
        }
        return self::$parameters['parameters'][$key];
    }

    /**
     * AbstractConfiguration constructor.
     *
     * Made private to forbid instanciation
     */
    protected function __construct()
    {
    }
}