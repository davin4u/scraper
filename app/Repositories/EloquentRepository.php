<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentRepository
 * @package App\Repositories
 */
class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $take = 10;

    /**
     * @var string
     */
    protected $orderBy = 'id';

    /**
     * @var array
     */
    protected $whereClause = [];

    /**
     * @var array
     */
    protected $whereLikeClause = [];

    /** @var Model */
    protected $model;

    /**
     * EloquentRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!property_exists($this, 'model') || !$this->model) {
            throw new \Exception("Property model must be set in child repository class.");
        }

        $this->model = new $this->model;
    }

    /**
     * @param $field
     * @param string $operator
     * @param null $value
     * @return EloquentRepositoryInterface
     */
    public function where($field, $operator = '=', $value = null) : EloquentRepositoryInterface
    {
        if (is_null($value)) {
            $value    = $operator;
            $operator = '=';
        }

        $this->whereClause[] = [$field, $operator, $value];

        return $this;
    }

    /**
     * @param $field
     * @param $pattern
     * @return $this
     */
    public function whereLike($field, $pattern) : EloquentRepositoryInterface
    {
        $this->whereLikeClause[] = [$field, 'like', $pattern];

        return $this;
    }

    /**
     * @param int $number
     * @return EloquentRepositoryInterface
     */
    public function offset(int $number = 0) : EloquentRepositoryInterface
    {
        $this->offset = $number;

        return $this;
    }

    /**
     * @param int $number
     * @return EloquentRepositoryInterface
     */
    public function take(int $number = 10) : EloquentRepositoryInterface
    {
        $this->take = $number;

        return $this;
    }

    /**
     * @param string $field
     * @return EloquentRepositoryInterface
     */
    public function orderBy(string $field) : EloquentRepositoryInterface
    {
        $this->orderBy = $field;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        $query = $this->buildQuery();

        return $query->get();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate() : LengthAwarePaginator
    {
        $query = $this->buildQuery();

        return $query->paginate();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery()
    {
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $this->model->newQuery();

        if (!empty($this->whereLikeClause)) {
            foreach ($this->whereLikeClause as $item) {
                $this->whereClause[] = [$item[0], $item[1], $item[2]];
            }
        }

        if (!empty($this->whereClause)) {
            foreach ($this->whereClause as $item) {
                $query->where($item[0], $item[1], $item[2]);
            }
        }

        if (!is_null($this->orderBy)) {
            $query->orderBy($this->orderBy);
        }

        if (!is_null($this->offset)) {
            $query->offset($this->offset);
        }

        if (!is_null($this->take)) {
            $query->take($this->take);
        }

        return $query;
    }
}
