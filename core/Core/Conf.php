<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Class Conf
 * @package Core
 */
class                           Conf {
    use                         StaticDotAccessor;

    /**
     * Load a conf file into $__data.
     *
     * @param string $file      Conf file path
     * @return bool             TRUE if the conf file is loaded.
     */
    public static function      load($file) {
        if (!file_exists($file))
            return false;
        self::$__data = json_decode(file_get_contents($file), true);
        return true;
    }
}