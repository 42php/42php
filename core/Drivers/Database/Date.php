<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Database;

/**
 * Gère les dates dans les drivers
 *
 * Interface Date
 * @package Drivers\Database
 */
interface                       Date {
    /**
     * Formatte une date dans le format propre au driver
     *
     * @param int $timestamp Timestamp
     * @param bool $withTime Inclure le temps
     * @return mixed
     */
    public static function      format($timestamp, $withTime = true);
}