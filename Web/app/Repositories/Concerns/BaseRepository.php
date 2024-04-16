<?php

namespace App\Repositories\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository as BRepository;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Events\RepositoryEntityUpdating;

abstract class BaseRepository extends BRepository
{
    /**
     * Fillables of eloquent model
     *
     * @var array
     */
    protected $allColumns;

    /**
     * Get detail of contract with trashed record
     *
     * @param int | string $id
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getDetail($id)
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->where('id', $id)->firstOrFail();

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Basic insert to database with eloquent
     *
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function insert(array $attributes)
    {
        return $this->model->insert($attributes);
    }

    /**
     * Update a entity in repository by model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function updateByModel(\Illuminate\Database\Eloquent\Model $model, array $attributes)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        event(new RepositoryEntityUpdating($this, $model));

        $model->fill($attributes);
        $model->save();

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update multi rows
     *
     * @param  array  $values  [id => value]
     * @param  string  $column  column of table in database
     *
     * @return mixed
     */
    public function updateMulti(array $values, string $column)
    {
        $table  = $this->model->getTable();
        $cases  = [];
        $ids    = [];
        $params = [];

        // Get settings of value with array ([id => value])
        // Then add query methods and convert it into a string for Datbase builder
        foreach ($values as $id => $value) {
            $id       = (int) $id;
            $cases[]  = "WHEN {$id} then ?";
            $params[] = $value;
            $ids[]    = $id;
        }

        $ids   = implode(',', $ids);
        $cases = implode(' ', $cases);

        $timestamp = '';
        if ($this->model->usesTimestamps() == true) {
            $params[]  = Carbon::now();
            $timestamp = ', `updated_at` = ?';
        }

        return DB::update("UPDATE `{$table}` SET `" . $column . "` = CASE `id` {$cases} END " .
                          $timestamp . " WHERE `id` in ({$ids})", $params);
    }

    /**
     * Custom eloquent builder
     *
     * @param $conditions
     * @param $builder
     *
     * @return mixed
     */
    public function whereBuilder($conditions, $builder)
    {
        if ($conditions instanceof Collection) {
            $conditions = $conditions->filter(function ($value) {
                return !(empty($value) && $value !== '0');
            })->all();
        }

        // Parse custom conditions installed from control or service
        // Then add item of condition with query builder
        // Item need available in fillable of Eloquent model
        foreach ($conditions as $field => $value) {
            if (is_array($value) && !empty($value) && count($value) > 2) {
                [$field, $condition, $val] = $value;
                if (!$this->isFillable($field)) {
                    continue;
                }

                $builder = $builder->where($field, $condition, $val);
            } else {
                if (!$this->isFillable($field)) {
                    continue;
                }

                $builder = $builder->where($field, '=', $value);
            }
        }

        return $builder;
    }

    /**
     * Custom Order by of eloquent builder
     *
     * @param array $sorts Example: ['sort' => ['created_at' => 'ASC']]
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param bool $useStrict
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBuilder($sorts, $builder, bool $useStrict = true)
    {
        $fillables = array_unique(array_merge($this->getFillable(), [$this->model->getCreatedAtColumn()]));

        if (empty($sorts) && in_array('id', $fillables)) {
            $sorts = [($this->model->getKeyType() == 'string' ? $this->model->getCreatedAtColumn() : 'id') => 'DESC'];
        }

        if (empty($sorts)) {
            return $builder;
        }

        if (!is_array($sorts)) {
            $sorts = [$sorts];
        }

        // Get all fields sort from query parameters and set to order of Query builder
        foreach ($sorts as $k => $v) {
            if (!in_array($k, $fillables) && $useStrict) {
                continue;
            }

            $builder = $builder->orderBy($k, $v);
        }

        return $builder;
    }

    /**
     * Custom Order by of eloquent builder without strict mode
     *
     * @param $sorts
     * @param $builder
     *
     * @return mixed
     */
    public function orderBuilderWithoutStrict($sorts, $builder)
    {
        return $this->orderBuilder($sorts, $builder, false);
    }

    /**
     * Get fillable from eloquent model
     *
     * @return array
     */
    public function getFillable()
    {
        $fillables  = $this->model->getFillable();
        $primaryKey = $this->model->getKeyName();

        if (!in_array($primaryKey, $fillables)) {
            array_push($fillables, $primaryKey);
        }

        $this->allColumns = $fillables;

        return $fillables;
    }

    /**
     * Existence check in fillable eloquent model
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isFillable(string $key)
    {
        if (!$this->allColumns) {
            $this->getFillable();
        }

        if (!in_array($key, $this->allColumns)) {
            return false;
        }

        return true;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * Multiple update columns database
     *
     * @param $table
     * @param  string  $ids
     * @param  string  $values
     * @param  array  $params
     */
    public function sort($table, string $ids, string $values, array $params)
    {
        DB::update("UPDATE `{$table}` SET `sort` = CASE `id` {$values} END, `updated_at` = ?
            WHERE `id` in ({$ids})", $params);
    }
}
