<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Drivers\Database\PDO;

/**
 * Handles IDs with PDO
 *
 * Class Id
 * @package Drivers\Database\PDO
 */
class                       Id implements \Drivers\Database\Id {
    /**
     * Formats an ID
     *
     * @param int $id       ID
     * @return int
     */
    public static function  format($id) {
        return intval($id);
    }
}