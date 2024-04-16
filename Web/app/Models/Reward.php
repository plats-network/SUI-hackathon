<?php

namespace App\Models;

use App\Helpers\BaseImage;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Storage;

/**
 * Attributes
 * @property uuid $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $type
 * @property integer $region
 * @property datatime $created_at
 * @property datatime $updated_at
 */

class Reward extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'symbol',
        'region',
        'order',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BaseImage::loadImage($value)
        );
    }

    protected function region(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (string)$value
        );
    }

    protected function order(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (string)$value
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (string)$value
        );
    }
}
