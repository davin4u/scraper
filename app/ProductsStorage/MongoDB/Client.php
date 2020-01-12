<?php

namespace App\ProductsStorage\MongoDB;

use App\Exceptions\MethodNotFoundException;
use App\ProductsStorage\Interfaces\MongoDBClientInterface;
use App\ProductsStorage\Interfaces\MongoDBCollectionInterface;

/**
 * Class Client
 * @package App\ProductsStorage\MongoDB
 */
class Client implements MongoDBClientInterface
{
    /**
     * @var Client
     */
    protected static $instance;

    /**
     * @var \MongoDB\Client
     */
    protected $client;

    /**
     * Client constructor.
     */
    final public function __construct()
    {
        $user       = env('MONGODB_USER');
        $password   = env('MONGODB_PASSWORD');
        $host       = env('MONGODB_HOST');
        $port       = env('MONGODB_PORT');
        $db         = env('MONGODB_DATABASE');

        $this->client = new \MongoDB\Client("mongodb://$user:$password@$host:$port/$db");
    }

    /**
     * @param $name
     * @return Collection
     */
    public function collection(string $name) : MongoDBCollectionInterface
    {
        return new Collection($this->client->selectCollection(env('MONGODB_DATABASE'), $name));
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public static function __callStatic($name, $arguments)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        if (method_exists(static::$instance, $name)) {
            return $arguments ? static::$instance->{$name}($arguments) : static::$instance->{$name}();
        }

        throw new MethodNotFoundException("Method $name does not exist.");
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        if (method_exists(static::$instance, $name)) {
            return $arguments ? static::$instance->{$name}($arguments) : static::$instance->{$name}();
        }

        throw new MethodNotFoundException("Method $name does not exist.");
    }

    /**
     * @return mixed
     */
    final public function __clone()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
