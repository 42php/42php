<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Drivers\Database\PDO;

/**
 * Handles regex with PDO
 *
 * Class Id
 * @package Drivers\Database\PDO
 */
class                       Regex implements \Drivers\Database\Regex {
    /**
     * Format a regular expression
     *
     * @param string $regex Regexp
     * @return string       Formatted regexp
     */
    public static function  format($regex) {
        $delimiter = substr($regex, 0, 1);
        $last = strrpos($regex, $delimiter);
        return substr($regex, 1, $last - 1);
    }
}