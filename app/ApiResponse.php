<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var mixed
     */
    protected $data;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        $this->data = json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return Arr::get($this->data, 'data', []);
    }

    /**
     * @return mixed
     */
    public function meta()
    {
        return Arr::get($this->data, 'meta', null);
    }

    /**
     * @return MessageBag
     */
    public function errors() : MessageBag
    {
        $errors = new MessageBag();

        if (isset($this->data['error'])) {
            $errors->add('error', $this->data['error']);
        }

        if (isset($this->data['errors']) && ! empty($this->data['errors'])) {
            foreach ($this->data['errors'] as $key => $message) {
                $errors->add($key, $message);
            }
        }

        return $errors;
    }

    /**
     * @return bool
     */
    public function withErrors()
    {
        return !in_array($this->response->getStatusCode(), [200, 201, 202, 204]);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!empty($this->data) && isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }
}
