<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait Recordable
{
    /**
     * Boot the Recordable trait for a model.
     *
     * @return void
     */
    public static function bootRecordable()
    {
        // Not handler for guest
        if (Auth::guest()) {
            return;
        }

        // Listen Observer
        static::creating(function ($model) {
            $model->{$model->getCreatedByColumn()} = $model->{$model->getUpdatedByColumn()} = Auth::id();
        });

        static::updating(function ($model) {
            $model->{$model->getUpdatedByColumn()} = Auth::id();
        });
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }

    /**
     * Get the name of the "updated by" column.
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }
}
