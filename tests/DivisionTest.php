<?php
namespace BDLocation\Tests;

use BDLocation\BD;
use PHPUnit\Framework\TestCase;

class DivisionTest extends TestCase
{
    public function testAll()
    {
        $divisions = BD::division()->all();
        $this->assertEquals(8, count($divisions));
    }

    public function testEqualQuery()
    {
        $divisions = BD::division()->getWhere('name', 'Chittagong');

        $this->assertTrue($divisions->name == 'Chittagong');

        $divisions = BD::division()->getWhere('name', '=', 'Chittagong');

        $this->assertTrue($divisions->name == 'Chittagong');
    }

    public function testLikeQuery()
    {
        $divisions = BD::division()->getWhere('name', 'like', 'hittag');
        $this->assertTrue($divisions[0]->name == 'Chittagong');
    }
}
