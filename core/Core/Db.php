<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Core;

/**
 * Handles DB queries
 *
 * Class Db
 * @package Core
 */
class                               Db {
    /**
     * Calls the Factory of the configured driver. (\Drivers\Database\nomDuDriver\Factory::getInstance())
     *
     * @return mixed                The DB instance
     */
    public static function          getInstance() {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Factory';
        if (class_exists($factory))
            return $factory::getInstance();
        return false;
    }

    /**
     * Format a date, with ou without time.
     *
     * @param bool|int $timestamp   Timestamp
     * @param bool $withTime        Include time
     * @return mixed                Formatted date
     */
    public static function          date($timestamp = false, $withTime = true) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Date';
        if (class_exists($factory))
            return $factory::format($timestamp === false ? time() : $timestamp, $withTime);
        return false;
    }

    /**
     * Format the document ID
     *
     * @param mixed $id             Document ID
     * @return mixed                Formatted ID
     */
    public static function          id($id = false) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Id';
        if (class_exists($factory))
            return $factory::format($id);
        return false;
    }

    /**
     * Format a regular expression
     *
     * @param string $regex     Regexp
     * @return mixed            Formatted Regexp
     */
    public static function          regex($regex) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Regex';
        if (class_exists($factory))
            return $factory::format($regex);
        return false;
    }
}