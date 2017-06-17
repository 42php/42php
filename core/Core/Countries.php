<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Class Countries
 * @package Core
 */
class                           Countries {
    /**
     * Get a list of all countries
     *
     * @return array
     */
    public static function      getList() {
        $file = ROOT . '/vendor/umpirsky/countries/' . Conf::get('lang') . '/country.json';
        return json_decode(file_get_contents($file), true);
    }
}