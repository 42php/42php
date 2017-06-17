<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Argv;
use Core\Conf;
use Core\Http;

/**
 * If the system is running from CLI, we have to set the $_SERVER variables.
 */
if (!isset($_SERVER['SERVER_NAME']))
    $_SERVER['SERVER_NAME'] = 'cli';
if (!isset($_SERVER['SERVER_PORT']))
    $_SERVER['SERVER_PORT'] = 80;

/**
 * Processing argc and argv
 */
global $argv, $argc;
$argv = Argv::parse(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', Conf::get('argv.offset', 0));
$argc = sizeof($argv);
Conf::set('url', Http::url());