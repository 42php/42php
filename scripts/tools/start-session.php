<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Http;
use Core\Session;

$headers = Http::headers();

if (isset($headers['X-Token']))
    Session::id($headers['X-Token']);

session_start();

Session::init();