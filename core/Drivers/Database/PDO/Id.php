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
class                       Id implements \Drivers\Database\Id {
    /**
     * Formatte un identifiant de document pour le SQL
     *
     * @param int $id       ID
     * @return int          L'ID standardisé
     */
    public static function  format($id) {
        \Core\Debug::trace();
        return intval($id);
    }
}