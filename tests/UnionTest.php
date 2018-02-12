<?php

use BDLocation\BD;

class UnionTest extends \PHPUnit\Framework\TestCase
{
    public function testAll()
    {
        $unions = BD::union()->all();
        $this->assertEquals(2350, count($unions));
    }

    public function testEqualQuery()
    {
        $districts = BD::union()->getWhere('sub_district', 'sar');
        $this->assertEquals(21, count($districts));
    }
}