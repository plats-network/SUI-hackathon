<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid as UuidBase;

trait Uuid
{
    /**
     * Boot the Model.
     */
    public static function bootUuid()
    {
        static::creating(function ($model) {
            $model->generateUuid($model);
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    private function generateUuid($model)
    {
        if ($model->incrementing && !$model->getKey()) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        }
    }
}
