<?php
namespace BDLocation\Tests;

use BDLocation\BD;
use PHPUnit\Framework\TestCase;

class DistrictTest extends TestCase
{
    public function testAll()
    {
        $districts = BD::district()->all();
        $this->assertEquals(64, count($districts));
    }

    public function testEqualQuery()
    {
        $districts = BD::district()->getWhere('division', 'chi');
        $this->assertEquals(11, count($districts));

        $districts = BD::district()->getWhere('name', 'brahmanbaria');

        $this->assertTrue($districts->name == 'Brahmanbaria');

        $districts = BD::district()->getWhere('name', '=', 'brahmanbaria');

        $this->assertTrue($districts->name == 'Brahmanbaria');
    }

    public function testLikeQuery()
    {
        $districts = BD::district()->getWhere('name', 'like', 'brahm');
        $this->assertTrue($districts[0]->name == 'Brahmanbaria');
    }
}
