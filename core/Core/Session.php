<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;


class                           Session {
    use                         StaticDotAccessor;

    public static function      init() {
        self::$__data = $_SESSION;
    }

    public static function      onChange() {
        $_SESSION = self::$__data;
    }
}