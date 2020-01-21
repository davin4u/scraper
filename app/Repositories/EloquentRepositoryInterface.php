<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @return mixed
     */
    public function get();

    /**
     * @return LengthAwarePaginator
     */
    public function paginate() : LengthAwarePaginator;

    /**
     * @param int $number
     * @return EloquentRepositoryInterface
     */
    public function offset(int $number) : EloquentRepositoryInterface;

    /**
     * @param int $number
     * @return EloquentRepositoryInterface
     */
    public function take(int $number) : EloquentRepositoryInterface;

    /**
     * @param string $field
     * @return EloquentRepositoryInterface
     */
    public function orderBy(string $field) : EloquentRepositoryInterface;

    /**
     * @param string $field
     * @param $operator
     * @param $value
     * @return EloquentRepositoryInterface
     */
    public function where(string $field, $operator, $value) : EloquentRepositoryInterface;

    /**
     * @param string $field
     * @param $pattern
     * @return EloquentRepositoryInterface
     */
    public function whereLike(string $field, $pattern) : EloquentRepositoryInterface;
}
