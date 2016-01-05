<?php
/**
 * Main entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

const ROOT_PATH = __DIR__;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

$app = new Silex\Application();

$app->run();
