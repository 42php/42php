<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use                     PHPUnit\Framework\TestCase;

final class             ArrayToolsTest extends TestCase {
    public function     testIsAssociative() {
        $this->assertTrue(
            \Core\ArrayTools::isAssociative(['test' => 1, 2 => 3, '4' => 5])
        );
    }

    public function     testIsSequential() {
        $this->assertFalse(
            \Core\ArrayTools::isAssociative([1,2,3,4,5])
        );
    }
}