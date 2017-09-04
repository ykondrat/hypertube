<?php


namespace GeoIPUnit;


use lysenkobv\GeoIP\Location;

class LocationTest extends TestCase {
    public function testProperties() {
        $lat = 40.1;
        $lng = 40.2;

        $location = new Location($lat, $lng);

        $this->assertEquals($location->lat, $lat);
        $this->assertEquals($location->lng, $lng);

        $this->assertInternalType("float", $location->lat);
        $this->assertInternalType("float", $location->lng);
    }
}
