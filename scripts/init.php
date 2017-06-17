<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

define('ROOT', realpath(__DIR__.'/../'));

require_once ROOT . '/scripts/tools/disable-buffer.php';
require_once ROOT . '/scripts/tools/autoload.php';
require_once ROOT . '/scripts/tools/reset-env.php';
require_once ROOT . '/scripts/tools/routing.php';
require_once ROOT . '/scripts/tools/detect-site.php';
require_once ROOT . '/scripts/tools/debug.php';
require_once ROOT . '/scripts/tools/start-session.php';
require_once ROOT . '/scripts/tools/load-conf.php';