<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRewardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => optional($this->reward)->name,
            'description' => optional($this->reward)->description,
            'image' => optional($this->reward)->image,
            'symbol' => optional($this->reward)->symbol,
            'amount' => $this->amount,
        ];
    }
}
