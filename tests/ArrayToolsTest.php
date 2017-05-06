<?php

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