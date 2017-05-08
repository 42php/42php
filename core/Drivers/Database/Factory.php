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

namespace                   Drivers\Database;

/**
 * Interface Factory
 * @package Drivers\Database
 */
interface                   Factory {
    /**
     * Récupère une instance singleton du driver
     * @return mixed
     */
    public static function  getInstance();

    /**
     * Ferme la connexion
     * @return mixed
     */
    public function         close();

    /**
     * Récupère un objet Collection.
     * @param string $k Nom de la collection
     * @return mixed
     */
    public function         __get($k);
}