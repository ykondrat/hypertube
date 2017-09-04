<?php


namespace GeoIPUnit;


class ResultTest extends TestCase {
    public function testIsDetected() {
        $items = [
            "72.229.28.185" => true,
            "93.210.15.68" => true,
            "127.0.0.1" => false
        ];

        foreach ($items as $ip => $expect) {
            $result = $this->result($ip);
            $actual = $result->isDetected();
            $this->assertEquals($expect, $actual);
        }
    }

    public function testCity() {
        $items = [
            "72.229.28.185" => "New York",
            "208.113.83.165" => "Millbrae",
        ];

        foreach ($items as $ip => $expect) {
            $result = $this->result($ip);
            $actual = $result->city;
            $this->assertEquals($expect, $actual);
        }
    }

    public function testCountry() {
        $items = [
            "72.229.28.185" => "United States",
            "93.210.15.68" => "Germany",
        ];

        foreach ($items as $ip => $expect) {
            $result = $this->result($ip);
            $actual = $result->country;
            $this->assertEquals($expect, $actual);
        }
    }

    public function testIsoCode() {
        $items = [
            "93.210.15.68" => "DE",
            "177.140.143.40" => "BR",
        ];

        foreach ($items as $ip => $expect) {
            $result = $this->result($ip);
            $actual = $result->isoCode;
            $this->assertEquals($expect, $actual);
        }
    }

    public function testLocationType() {
        $items = [
            "72.229.28.185" => "float",
            "93.210.15.68" => "float",
            "127.0.0.1" => "null",
        ];

        foreach ($items as $ip => $expect) {
            $result = $this->result($ip);
            $actual = $result->location;

            $this->assertInternalType($expect, $actual->lat);
            $this->assertInternalType($expect, $actual->lng);
        }
    }
}
