<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Handle API errors
 *
 * Class ApiException
 * @package Core
 */
class                       ApiException extends \Exception {
    /**
     * Displays the error message
     *
     * @return string
     */
    public function         __toString() {
        return __CLASS__ . ": [{$this->code}] {$this->message}\n";
    }
}