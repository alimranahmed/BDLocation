<?php
namespace BDLocation\Tests;

use BDLocation\BD;
use PHPUnit\Framework\TestCase;

class UnionTest extends TestCase
{
    public function testAll()
    {
        $unions = BD::union()->all();
        $this->assertEquals(2350, count($unions));
    }

    public function testEqualQuery()
    {
        $unions = BD::union()->getWhere('sub_district', 'sarai');
        $this->assertEquals(9, count($unions));
    }
}
