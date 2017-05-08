<?php
/**
 * LICENSE: This source file is subject to version 3.0 of the GPL license
 * that is available through the world-wide-web at the following URI:
 * https://www.gnu.org/licenses/gpl-3.0.fr.html (french version).
 *
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://www.gnu.org/licenses/gpl-3.0.fr.html GPL
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