<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Drivers\Database\PDO;

/**
 * Handle dates with PDO
 *
 * Class Date
 * @package Drivers\Database\PDO
 */
class                       Date implements \Drivers\Database\Date {
    /**
     * Formats a date
     *
     * @param int $timestamp        Timestamp
     * @param bool $withTime        Include time
     * @return string               Formatted date Y-m-d H:i:s
     */
    public static function  format($timestamp, $withTime = true) {
        if ($withTime)
            return date('Y-m-d H:i:s', $timestamp);
        return date('Y-m-d', $timestamp);
    }
}