<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Class ArrayTools
 * @package Core
 */
class                       ArrayTools {
    /**
     * Checks if an array is associative
     *
     * @param array $arr    The array to test
     * @return bool         TRUE if the array is associative
     */
    public static function  isAssociative(array $arr) {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, sizeof($arr) - 1);
    }
}