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
 * Gère les id sous PDO
 *
 * Class Id
 * @package Drivers\Database\PDO
 */
class                       Regex implements \Drivers\Database\Regex {
    /**
     * Formatte une expression régulière
     *
     * @param string $regex L'expression régulière
     * @return string       L'expression régulière formattée
     */
    public static function  format($regex) {
        \Core\Debug::trace();
        $delimiter = substr($regex, 0, 1);
        $last = strrpos($regex, $delimiter);
        return substr($regex, 1, $last - 1);
    }
}