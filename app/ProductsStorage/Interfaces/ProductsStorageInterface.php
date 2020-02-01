<?php

namespace App\ProductsStorage\Interfaces;

use Illuminate\Support\Collection;

/**
 * Interface ProductsStorageInterface
 * @package App\ProductsStorage\Interfaces
 */
interface ProductsStorageInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param array $filter
     * @param array $options
     * @return array
     */
    public function where($filter = [], array $options = []) : array;

    /**
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, $attributes);

    /**
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
