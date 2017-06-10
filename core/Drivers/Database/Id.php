<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Database;

/**
 * Gère les identifiants de documents dans les drivers
 *
 * Interface Id
 * @package Drivers\Database
 */
interface                       Id {
    /**
     * Formatte un identifiant de document pour le driver configuré
     *
     * @param mixed $id         Identifiant
     * @return mixed            L'identifiant formatté
     */
    public static function      format($id);
}