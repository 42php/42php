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

namespace                   Drivers\Database\PDO;

/**
 * Gère les dates sous PDO
 *
 * Class Date
 * @package Drivers\Database\PDO
 */
class                       Date implements \Drivers\Database\Date {
    /**
     * Formatte une date pour le SQL
     *
     * @param int $timestamp        Timestamp
     * @param bool $withTime        Inclure le temps
     * @return string               La date formattée Y-m-d H:i:s
     */
    public static function  format($timestamp, $withTime = true) {
        \Core\Debug::trace();
        if ($withTime)
            return date('Y-m-d H:i:s', $timestamp);
        return date('Y-m-d', $timestamp);
    }
}