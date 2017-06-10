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

    /**
     * Applies array_merge recursively.
     *
     * @param array $arr1       The first array
     * @param array $arr2       The second array
     * @param string $exclude   The value to exclude
     *
     * @return array            The merged array.
     */
    public static function      recursiveMerge($arr1, $arr2, $exclude = '') {
        $ret = $arr1;
        if (!is_array($ret))
            return $arr2;
        foreach ($arr2 as $k => $v) {
            if (isset($ret[$k])) {
                if (is_array($v)) {
                    $ret[$k] = self::recursiveMerge($ret[$k], $v);
                } elseif ($v !== $exclude)
                    $ret[$k] = $v;
            } else {
                $ret[$k] = $v;
            }
        }
        return $ret;
    }

    /**
     * Returns the value in an array, if $val isn't an array.
     *
     * @param mixed $val        The value
     *
     * @return array            The array
     */
    public static function      getAsArray($val) {
        if (!is_array($val))
            $val = [$val];
        return $val;
    }
}