<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Class Session
 * @package Core
 */
class                           Session {
    use                         StaticDotAccessor;

    /**
     * Initialize the session.
     */
    public static function      init() {
        self::$__data = $_SESSION;
    }

    /**
     * On every local change, we save the data in the session.
     */
    public static function      onChange() {
        $_SESSION = self::$__data;
    }

    /**
     * Saves the session content
     */
    public static function      save() {
        session_write_close();
        session_start();
    }

    public static function      id($newId = false) {
        if ($newId !== false)
            session_id($newId);
        return session_id();
    }
}