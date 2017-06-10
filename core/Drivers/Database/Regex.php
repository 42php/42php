<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Database;

/**
 * Gère les expressions régulières pour le driver configuré
 *
 * Interface Regex
 * @package Drivers\Database
 */
interface                       Regex {
    /**
     * Regex constructor.
     *
     * @param string $regex     L'expression régulière
     * @return mixed            L'expression régulière formattée
     */
    public static function      format($regex);
}