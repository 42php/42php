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