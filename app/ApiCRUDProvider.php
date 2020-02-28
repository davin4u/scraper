<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class ApiCRUDProvider
 * @package App
 */
class ApiCRUDProvider
{
    /**
     * @var array|\Illuminate\Config\Repository|mixed
     */
    protected $endpoints = [];

    /**
     * @var mixed|string|null
     */
    protected $api_token = '';

    /**
     * ApiCRUDProvider constructor.
     */
    public function __construct()
    {
        $this->endpoints = config('api.endpoints', []);

        if (Auth::user()) {
            $this->api_token = Arr::get(config('api.tokens'), Auth::user()->id, null);
        }
        else if (app()->runningInConsole()) {
            $this->api_token = Arr::get(config('api.tokens'), 1, null);
        }

        if (is_null($this->api_token)) {
            throw new \Exception("Forbidden.", 403);
        }
    }

    /**
     * @param $entity
     * @param array $data
     * @param string $method
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function index($entity, $data = [], $method = '')
    {
        $endpoint = $this->getEndpoint($entity, $method);

        if (!empty($data)) {
            $endpoint .= '&' . http_build_query($data);
        }

        return $this->request('GET', $endpoint);
    }

    /**
     * @param $entity
     * @param int $id
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function show($entity, int $id)
    {
        return $this->request('GET', $this->getEndpoint($entity, $id));
    }

    /**
     * @param $entity
     * @param array $data
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function store($entity, $data = [])
    {
        return $this->request('POST', $this->getEndpoint($entity), $data);
    }

    /**
     * @param $entity
     * @param int $id
     * @param array $data
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function update($entity, int $id, $data = [])
    {
        return $this->request('PUT', $this->getEndpoint($entity, $id), $data);
    }

    /**
     * @param $entity
     * @param int $id
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function destroy($entity, int $id)
    {
        return $this->request('DELETE', $this->getEndpoint($entity, $id));
    }

    /**
     * @param $entity
     * @param array $data
     * @param null $idOrMethod
     * @return ApiResponse|null
     * @throws \Exception
     */
    public function post($entity, $data = [], $idOrMethod = null)
    {
        return $this->request('POST', $this->getEndpoint($entity, $idOrMethod), $data);
    }

    /**
     * @param $method
     * @param $url
     * @param array $data
     * @return ApiResponse|null
     */
    protected function request($method, $url, $data = [])
    {
        $client = new Client();

        $options = [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];

        if (!empty($data)) {
            $options['form_params'] = $data;
        }

        try {
            $response = $client->request($method, $url, $options);

            return new ApiResponse($response);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());

            return null;
        }
    }

    /**
     * @param $entity
     * @param null $idOrMethod
     * @return string
     * @throws \Exception
     */
    protected function getEndpoint($entity, $idOrMethod = null)
    {
        if (!isset($this->endpoints[$entity])) {
            throw new \Exception("Api endpoint not found.");
        }

        $endpoint = $this->endpoints[$entity];

        if (!is_null($idOrMethod)) {
            $endpoint .= '/' . $idOrMethod;
        }

        return $endpoint . '?api_token=' . $this->api_token;
    }
}
