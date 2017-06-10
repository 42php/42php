<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
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

    /**
     * Quote a string
     * @param string $k     String
     * @return string
     */
    public function         quote($k);
}