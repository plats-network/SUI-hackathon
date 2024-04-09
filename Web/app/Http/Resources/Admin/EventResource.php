<?php

namespace App\Http\Resources\Admin;

use App\Helpers\BaseImage;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
