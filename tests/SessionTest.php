<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use                     PHPUnit\Framework\TestCase;

final class             SessionTest extends TestCase {
    public function     testChangeAffectActualSession() {
        \Core\Session::set('test', [
            'var' => 'val'
        ]);
        $this->assertArrayHasKey('test', $_SESSION);
        $this->assertArrayHasKey('var', $_SESSION['test']);
        $this->assertEquals('val', $_SESSION['test']['var']);
    }
}