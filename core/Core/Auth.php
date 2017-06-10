<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Core;

/**
 * Handles authentication
 *
 * Class Auth
 * @package Core
 */
class                               Auth {
    /**
     * Get the user id
     *
     * @return bool|mixed           If user is logged, the user id, or FALSE
     */
    public static function          uid() {
        if (Auth::logged())
            return Session::get('user.id');
        return false;
    }

    /**
     * Checks if user is logged or not
     *
     * @return bool
     */
    public static function          logged() {
        $uid = Session::get('user.id', false);
        if ($uid !== false)
            return true;
        return false;
    }

    /**
     * Checks if user is admin or not
     *
     * @return bool
     */
    public static function          admin() {
        return Session::get('user.admin', false);
    }

    /**
     * If user isn't logged, it redirects him to the login page
     *
     * @param bool $asAdmin         Determine if the user must be admin
     * @return bool
     */
    public static function          haveToBeLogged($asAdmin = false) {
        if (($asAdmin && !self::admin()) || (!$asAdmin && !self::logged())) {
            Redirect::http(
                Argv::createUrl('login') .
                '?redirect=' . urlencode($_SERVER['REQUEST_URI'])
            );
        }
        return true;
    }

    /**
     * Get user instance
     *
     * @return bool|\User           Get user instance
     */
    public static function          user() {
        if (!Auth::logged())
            return false;
        $user = new \User(Session::get('user.id', ''));
        return $user;
    }

    /**
     * Logout then redirects to a page.
     *
     * @param string|bool $redirect Redirection URL
     */
    public static function          logout($redirect = '/') {
        Session::destroy();
        if ($redirect !== false)
            Redirect::http($redirect);
    }
}