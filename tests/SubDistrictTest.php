<?php

namespace BDLocation\Tests;

use BDLocation\BD;
use PHPUnit\Framework\TestCase;

class SubDistrictTest extends TestCase
{
    public function testAll()
    {
        $subDistricts = BD::subDistrict()->all();
        //$this->assertEquals(491, count($subDistricts));
        $this->assertEquals(589, count($subDistricts));
    }

    public function testEqualQuery()
    {
        $districts = BD::subDistrict()->getWhere('district', 'bra');
        $this->assertEquals(10, count($districts));

        $subDistricts = BD::subDistrict()->getWhere('name', 'Sarail');

        $this->assertSame('Sarail', $subDistricts->name);

        $subDistricts = BD::subDistrict()->getWhere('name', '=', 'Sarail');

        $this->assertSame('Sarail', $subDistricts->name);
    }

    public function testLikeQuery()
    {
        $subDistricts = BD::subDistrict()->getWhere('name', 'like', 'sarai');
        $this->assertSame('Sarail', $subDistricts[0]->name);
    }

    public function testThana()
    {
        $districts = BD::subDistrict()->getWhere('district', 'dha');
        $this->assertEquals(54, count($districts));

        $subDistricts = BD::subDistrict()->getWhere('name', 'Adabor');

        $this->assertSame('Adabor', $subDistricts->name);
    }
}
