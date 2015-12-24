<?php
/**
 * Main entry point
 *
 * @author    Ivanis Kouamé <ivanis.kouame@smile.fr>
 * @copyright 2015 Smile
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->run();
