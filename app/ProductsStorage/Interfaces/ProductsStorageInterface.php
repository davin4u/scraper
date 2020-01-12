<?php

namespace App\ProductsStorage\Interfaces;

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
     * @param mixed ...$arguments
     * @return mixed
     */
    public function where(...$arguments);

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
