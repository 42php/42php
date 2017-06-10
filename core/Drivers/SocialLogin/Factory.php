<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\SocialLogin;

/**
 * Permet de se connecter à un provider pour authentifier un utilisateur
 *
 * Interface Factory
 * @package Drivers\SocialLogin
 */
interface                       Factory {
    /**
     * Récupère une instance singleton du driver
     *
     * @return mixed
     */
    public static function      getInstance();

    /**
     * Permet de se connecter à un provider pour authentifier un utilisateur
     *
     * @return bool
     */
    public function             login();
}