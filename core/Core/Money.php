<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Class Money
 */
class                       Money {
    /**
     * Round a financial variable
     *
     * @param float $amount         Amount
     *
     * @return float                Rounded amount
     */
    public static function  round($amount) {
        return round(floatval(str_replace(',', '.', $amount)), 2);
    }

    /**
     * Calculates the discount percentage
     *
     * @param float $full       Normal price
     * @param float $semi       Payed price
     *
     * @return float            Discount percent
     */
    public static function  discountPercent($full, $semi) {
        $full = self::round($full);
        $semi = self::round($semi);
        if ($full == 0)
            return 0;
        return round(($full - $semi) * 100 / $full, 2);
    }
}