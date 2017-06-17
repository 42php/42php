<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Core;

/**
 * Class Hash
 * @package Core
 */
class 								Hash {
    /**
     * Hash a string with blowfish
     *
     * @param string $input         Input string
     * @param int $rounds           Passes
     *
     * @return string               Hash
     */
    public static function 			blowfish($input, $rounds = 7) {
        $salt = "";
        $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        for ($i = 0; $i < 22; $i++) {
            $salt .= $salt_chars[array_rand($salt_chars)];
        }
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }

    /**
     * Checks if a string matches the hash
     *
     * @param string $entered       String
     * @param string $original      Hash
     *
     * @return bool
     */
    public static function 			same($entered, $original) {
        return crypt($entered, $original) == $original;
    }
}