<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use                     PHPUnit\Framework\TestCase;

final class             ConfTest extends TestCase {
    public function     testCanSetAndReadValue() {
        \Core\Conf::set('test', [
            'var1' => 1,
            'var2' => 2,
            'var3' => 3
        ]);
        $this->assertEquals(2, \Core\Conf::get('test.var2'));
    }

    public function     testCanAccessSequentialValue() {
        \Core\Conf::set('test', [
            ['test' => 1],
            ['test' => 2],
            ['test' => 3]
        ]);
        $this->assertEquals(1, \Core\Conf::get('test.test'));
    }

    public function     testCanExpectValue() {
        \Core\Conf::set('test', [
            'var1' => 1,
            'var2' => 2,
            'var3' => 3
        ]);
        $this->assertEquals('nope', \Core\Conf::get('test.var2', 'nope', 1));
    }

    public function     testCanExpectSequentialValue() {
        \Core\Conf::set('test', [
            ['test' => 1],
            ['test' => 2],
            ['test' => 3]
        ]);
        $this->assertEquals('nope', \Core\Conf::get('test.test', 'nope', 4));
    }
}