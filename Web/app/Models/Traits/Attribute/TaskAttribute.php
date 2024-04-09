<?php

namespace App\Models\Traits\Attribute;

use Illuminate\Support\Facades\Storage;

trait TaskAttribute {
    /**
     * @return string
     */
    public function getCoverUrlAttribute()
    {
        if (is_null($this->image)) {
            return 'https://via.placeholder.com/250x130?text=Cover Image';
        }

        return Storage::url($this->image);
    }

    /**
     * @param $value
     *
     * @return int
     */
    public function getTotalRewardAttribute($value)
    {
        return intval($value);
    }

    /**
     * @param $value
     *
     * @return int
     */
    public function getRewardAmountAttribute($value)
    {
        return intval($value);
    }
}
