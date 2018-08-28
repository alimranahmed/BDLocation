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
        $divisions = BD::division()->getWhere('name', 'Chattogram');

        $this->assertTrue($divisions->name == 'Chattogram');

        $divisions = BD::division()->getWhere('name', '=', 'Chattogram');

        $this->assertTrue($divisions->name == 'Chattogram');
    }

    public function testLikeQuery()
    {
        $divisions = BD::division()->getWhere('name', 'like', 'attogr');
        $this->assertTrue($divisions[0]->name == 'Chattogram');

        $divisions = BD::division()->getWhere('name', '%like', 'chatto');
        $this->assertTrue($divisions[0]->name == 'Chattogram');

        $divisions = BD::division()->getWhere('name', 'like%', 'gram');
        $this->assertTrue($divisions[0]->name == 'Chattogram');
    }
}
