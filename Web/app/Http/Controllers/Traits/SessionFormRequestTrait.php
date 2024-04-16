<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;

trait SessionFormRequestTrait
{

    /**
     * Flashing Input To The Session
     *
     * @param  array | Model  $attributes  [key => value]
     *
     * @return boolean
     */
    public function flashSession($attributes)
    {
        if ($attributes instanceof Model) {
            $attributes = $attributes->makeVisible($attributes->getHidden())->toArray();
        }

        // Add data to session and use with old() function
        foreach ($attributes as $key => $value) {
            if (request()->query($key)) {
                continue;
            }

            app('request')->merge([$key => $value]);
        }

        app('request')->flash();

        return true;
    }

    /**
     * Clean all session flash
     *
     * @return bool
     */
    public function flashReset()
    {
        if (!empty(old('id')) || old('id') != 0) {
            app('request')->flash();
        }

        return true;
    }
}
