<?php

namespace App\Helpers;


use Carbon\Carbon;

class DateHelper
{
    /**
     * Return date timestamp format.
     *
     * @param  Carbon|\App\Models\Instance\SpecialDate|string|null  $date
     * @return string|null
     */
    public static function getDateTime($date): ?string
    {
        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    /**
     * Return date timestamp format.
     *
     * @param  Carbon|\App\Models\Instance\SpecialDate|string|null  $date
     * @return string|null
     */
    public static function getDate($date): ?string
    {
        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->format('d-m-Y');
    }

    /**
     * Return date timestamp format.
     *
     * @param  Carbon|\App\Models\Instance\SpecialDate|string|null  $date
     * @return string|null
     */
    public static function getTime($date): ?string
    {
        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->format('H:i:s');
    }

    /**
     * Creates a Carbon object from DateTime format.
     * If timezone is given, it parse the date with this timezone.
     * Always return a date with default timezone (UTC).
     *
     * @param  \DateTime|Carbon|string|null  $date
     * @return Carbon|null
     */
    public static function parseDate($date): ?Carbon
    {
        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date);
    }

    /**
     * Return timestamp date format.
     *
     * @param  Carbon|\App\Models\Instance\SpecialDate|string|null  $date
     * @return string|null
     */
    public static function getTimestamp($date): ?string
    {
        if (is_null($date)) {
            return null;
        }

        return Carbon::parse($date)->timestamp;
    }
}
