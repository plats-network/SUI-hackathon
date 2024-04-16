<?php

namespace App\Services\Location;

interface CoordinateInterface
{
    /**
     * @return string
     */
    public function longitude();

    /**
     * @return string
     */
    public function latitude();
}
