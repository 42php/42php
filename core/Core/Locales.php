<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Class Locales
 * @package Core
 */
class                           Locales {
    /**
     * Get a list of all locales
     *
     * @return array
     */
    public static function      getList() {
        $file = ROOT . '/vendor/umpirsky/locales/' . Conf::get('lang') . '/locales.json';
        return json_decode(file_get_contents($file), true);
    }
}