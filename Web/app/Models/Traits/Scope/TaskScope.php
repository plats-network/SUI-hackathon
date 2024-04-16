<?php

namespace App\Models\Traits\Scope;

trait TaskScope {
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $localId Location ID
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasLocation($builder, $localId)
    {
        return $builder->whereHas('locations', function ($q) use ($localId) {
            $q->where('id', $localId);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $localId Location ID
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLocation($builder, $localId)
    {
        return $builder->with(['locations' => function ($q) use ($localId) {
            return $q->where('id', $localId);
        }]);
    }

    /**
     * @param $userId
     *
     * @return \App\Models\Task.\App\Models\Tasks
     */
    public function scopeWithUserStatus($userId)
    {
        return $this->load(['participants' => function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        } ]);
    }

    /**
     * @param $builder
     * @param $userId
     *
     * @return mixed
     */
    public function scopeUserJoinedTask($builder, $userId)
    {
        return $builder->whereHas('participants', function ($q) use ($userId) {
            return $q->where('user_id', $userId);
        });
    }
}
