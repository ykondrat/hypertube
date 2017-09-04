<?php


namespace lysenkobv\GeoIP;


class Location {

    /**
     * @var float|null
     */
    public $lat;

    /**
     * @var float|null
     */
    public $lng;

    public function __construct($lat = null, $lng = null) {
        $this->lat = $lat;
        $this->lng = $lng;
    }
}
