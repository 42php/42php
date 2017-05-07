<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Trait StaticDotAccessor
 * @package Core
 */
trait                           StaticDotAccessor {
    /**
     * @var array $__data       Stored data
     */
    public static 				$__data = [];
    /**
     * Reads a value
     *
     * @param string $var           Variable to return
     * @param bool $default         Default value
     * @param null|string $expect   Returns the value only if she matches this regexp
     * @param bool|array $forceData Force an array to use
     *
     * @return mixed                Returned value
     */
    public static function 		get($var, $default = false, $expect = null, $forceData = false) {
        if (is_scalar($expect) && !in_array(substr($expect, 0, 1), ['/', '#']))
            $expect = '/'.preg_quote($expect, '/').'/';
        if ($var == '')
            return $default;
        $els = explode('.', $var);
        $current = $forceData ? $forceData : self::$__data;
        $l = sizeof($els) - 1;
        foreach ($els as $i => $el) {
            if ($i < $l) {
                if (isset($current[$el]))
                    $current = $current[$el];
                else {
                    if (is_array($current) && !ArrayTools::isAssociative($current)) {
                        $newVar = [];
                        foreach ($els as $j => $e)
                            if ($j >= $i)
                                $newVar[] = $e;
                        foreach ($current as $a) {
                            $ret = self::get(implode('.', $newVar), null, $expect, $a);
                            if (!is_null($ret) && (is_null($expect) || preg_match($expect, $ret)))
                                return $ret;
                        }
                        return $default;
                    } else
                        return $default;
                }
            } else {
                if (isset($current[$el]) && (($expect && preg_match($expect, $current[$el])) || is_null($expect)))
                    return $current[$el];
                else {
                    if (is_array($current) && !ArrayTools::isAssociative($current)) {
                        $newVar = [];
                        foreach ($els as $j => $e)
                            if ($j >= $i)
                                $newVar[] = $e;
                        foreach ($current as $a) {
                            $ret = self::get(implode('.', $newVar), null, $expect, $a);
                            if (!is_null($ret) && (is_null($expect) || preg_match($expect, $ret)))
                                return $ret;
                        }
                        return $default;
                    } else
                        return $default;
                }
            }
        }
        return $default;
    }

    /**
     * Stores data recursively
     *
     * @param array $keys   Remaining keys
     * @param mixed $value  Value
     * @param array $data   Data array
     *
     * @return mixed
     */
    private static function 	recursiveSet($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            $data[$key] = $value;
            return $data;
        }
        $data[$key] = self::recursiveSet($keys, $value, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Stores a value
     *
     * @param string $k         Key
     * @param mixed $v          Value
     */
    public static function 		set($k, $v) {
        $k = explode('.', $k);
        self::$__data = self::recursiveSet($k, $v, self::$__data);
        if (method_exists(get_called_class(), 'onChange'))
            self::onChange();
    }

    /**
     * Appends a value to an array recursively
     *
     * @param array $keys       Remaining keys
     * @param mixed $value      Value
     * @param array $data       Data array
     *
     * @return mixed
     */
    private static function 	recursiveAppend($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            if (!isset($data[$key]))
                $data[$key] = [];
            $data[$key][] = $value;
            return $data;
        }
        $data[$key] = self::recursiveAppend($keys, $value, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Appends a value to an array
     *
     * @param string $k         Key
     * @param mixed $v          Value
     */
    public static function 		append($k, $v) {
        $k = explode('.', $k);
        self::$__data = self::recursiveAppend($k, $v, self::$__data);
        if (method_exists(get_called_class(), 'onChange'))
            self::onChange();
    }

    /**
     * Deletes a value recursively
     *
     * @param array $keys       Remaining keys
     * @param array $data       Data array
     *
     * @return mixed
     */
    private static function 	recursiveRemove($keys, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            unset($data[$key]);
            return $data;
        }
        $data[$key] = self::recursiveRemove($keys, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Deletes a value
     *
     * @param string $k         Key
     */
    public static function 		remove($k) {
        $k = explode('.', $k);
        self::$__data = self::recursiveRemove($k, self::$__data);
        if (method_exists(get_called_class(), 'onChange'))
            self::onChange(self::$__data);
    }

    /**
     * Returns the size of an array
     *
     * @param string $k         Key
     *
     * @return int              Size
     */
    public static function      size($k) {
        $el = self::get($k, []);
        if (!is_array($el))
            return 0;
        return sizeof($el);
    }

    /**
     * Calls a callback function with each key and value
     *
     * @param callable $callback    Callback with two parameters : key and value
     * @param bool $prefix          Prefix to use
     * @param null $data            Force an array to use as data
     */
    public static function 		values($callback, $prefix = false, $data = null) {
        if (is_null($data))
            $data = self::$__data;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                self::values($callback, ($prefix ? $prefix.'.' : '').$k, $v);
            }
            else
                $callback(($prefix ? $prefix.'.' : '').$k, $v);
        }
    }
}