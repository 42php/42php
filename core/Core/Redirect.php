<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace           Core;

/**
 * Handles redirections
 *
 * Class Redirect
 * @package Core
 */
class				Redirect {
    /**
     * Redirects permanently (with 301 HTTP code)
     *
     * @param string $to    Location
     */
    public static function	permanent($to) {
        header('HTTP/1.1 301 Moved Permanently', false, 301);
        self::http($to);
    }
    /**
     * Redirects temporarely (with 302 HTTP code)
     *
     * @param string $to    Location
     */
    public static function	http($to) {
        Session::save();
        header("Location: $to");
        die();
    }
}