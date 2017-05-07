<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

if (\Core\Conf::get('debug', false)) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
} else {
    ini_set('display_errors',0);
    ini_set('display_startup_errors',0);
}