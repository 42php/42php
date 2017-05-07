<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Trait DotAccessor
 * @package Core
 */
trait                           DotAccessor {
    /**
     * @var array $__data       Stored data
     */
    public 				        $__data = [];
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
    public function 	    	get($var, $default = false, $expect = null, $forceData = false) {
        if (is_scalar($expect) && !in_array(substr($expect, 0, 1), ['/', '#']))
            $expect = '/'.preg_quote($expect, '/').'/';
        if ($var == '')
            return $default;
        $els = explode('.', $var);
        $current = $forceData ? $forceData : $this->__data;
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
                            $ret = $this->get(implode('.', $newVar), null, $expect, $a);
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
                            $ret = $this->get(implode('.', $newVar), null, $expect, $a);
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
     * @param array $keys       Remaining keys
     * @param mixed $value      Value
     * @param array $data       Data array
     *
     * @return mixed
     */
    private function 	        recursiveSet($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            $data[$key] = $value;
            return $data;
        }
        $data[$key] = $this->recursiveSet($keys, $value, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Stores a value
     *
     * @param string $k         Key
     * @param mixed $v          Value
     */
    public function 	    	set($k, $v) {
        $k = explode('.', $k);
        $this->__data = $this->recursiveSet($k, $v, $this->__data);
        if (method_exists($this, 'onSave'))
            $this->onSave();
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
    private function 	        recursiveAppend($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            if (!isset($data[$key]))
                $data[$key] = [];
            $data[$key][] = $value;
            return $data;
        }
        $data[$key] = $this->recursiveAppend($keys, $value, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Appends a value to an array
     *
     * @param string $k         Key
     * @param mixed $v          Value
     */
    public function 	    	append($k, $v) {
        $k = explode('.', $k);
        $this->__data = $this->recursiveAppend($k, $v, $this->__data);
        if (method_exists($this, 'onSave'))
            $this->onSave();
    }

    /**
     * Deletes a value recursively
     *
     * @param array $keys       Remaining keys
     * @param array $data       Data array
     *
     * @return mixed
     */
    private function 	    recursiveRemove($keys, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);
        if ($insertValue) {
            unset($data[$key]);
            return $data;
        }
        $data[$key] = $this->recursiveRemove($keys, isset($data[$key]) ? $data[$key] : []);
        return $data;
    }

    /**
     * Deletes a value
     *
     * @param string $k         Key
     */
    public function 	    	remove($k) {
        $k = explode('.', $k);
        $this->__data = $this->recursiveRemove($k, $this->__data);
        if (method_exists($this, 'onSave'))
            $this->onSave($this->__data);
    }

    /**
     * Returns the size of an array
     *
     * @param string $k         Key
     *
     * @return int              Size
     */
    public function             size($k) {
        $el = $this->get($k, []);
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
    public function 	    	values($callback, $prefix = false, $data = null) {
        if (is_null($data))
            $data = $this->__data;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $this->values($callback, ($prefix ? $prefix.'.' : '').$k, $v);
            }
            else
                $callback(($prefix ? $prefix.'.' : '').$k, $v);
        }
    }
}