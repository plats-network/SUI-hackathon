<?php

namespace App\Services\Location;

class Coordinate implements CoordinateInterface
{
    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $latitude;

    /**
     * instance Coordinate by Longitude and Latitude
     *
     * @param string $long
     * @param string $lat
     */
    public function __construct($lat = null, $long = null)
    {
        $this->longitude = $long;
        $this->latitude  = $lat;
    }

    /**
     * Get Longitude and Latitude from strong of coordinate
     * Ex: 21.81522994706227, 105.23104174144201
     *
     * @param $string
     *
     * @return \App\Services\Location\CoordinateInterface|\App\Services\Location\Coordinate
     */
    public static function parse($string): CoordinateInterface|Coordinate
    {
        $coordinate = explode(',', preg_replace('/\s+/', '', $string));

        return new Coordinate($coordinate[0], $coordinate[1]);
    }

    /**
     * @return string
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function latitude()
    {
        return $this->latitude;
    }
}
