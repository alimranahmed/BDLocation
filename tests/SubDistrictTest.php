<?php
namespace BDLocation\Tests;

use BDLocation\BD;
use PHPUnit\Framework\TestCase;

class SubDistrictTest extends TestCase
{
    public function testAll()
    {
        $subDistricts = BD::subDistrict()->all();
        $this->assertEquals(491, count($subDistricts));
    }

    public function testEqualQuery()
    {
        $districts = BD::subDistrict()->getWhere('district', 'bra');
        $this->assertEquals(10, count($districts));

        $subDistricts = BD::subDistrict()->getWhere('name', 'Sarail Upazila');

        $this->assertTrue($subDistricts->name == 'Sarail Upazila');

        $subDistricts = BD::subDistrict()->getWhere('name', '=', 'Sarail Upazila');

        $this->assertTrue($subDistricts->name == 'Sarail Upazila');
    }

    public function testLikeQuery()
    {
        $subDistricts = BD::subDistrict()->getWhere('name', 'like', 'sarai');
        $this->assertTrue($subDistricts[0]->name == 'Sarail Upazila');
    }
}
