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