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

        $this->api_token = Arr::get(config('api.tokens'), Auth::user()->id, null);

        if (is_null($this->api_token)) {
            throw new \Exception("Forbidden.", 403);
        }
    }

    public function index($entity, $data = [])
    {
        $endpoint = $this->getEndpoint($entity);

        if (!empty($data)) {
            $endpoint .= '&' . http_build_query($data);
        }

        return $this->request('GET', $endpoint);
    }

    public function show($entity, int $id)
    {

    }

    public function store($entity, $data = [])
    {
        $endpoint = $this->getEndpoint($entity, 'POST');

        return $this->request('POST', $endpoint, $data);
    }

    public function update($entity, int $id, $data = [])
    {

    }

    public function destroy($entity, int $id)
    {

    }

    protected function request($method, $url, $data = [])
    {
        $client = new Client();

        if (strtoupper($method) === 'GET') {
            try {
                $response = $client->request(strtoupper($method), $url, [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);

                return new ApiResponse($response);
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());

                return null;
            }
        }

        if (strtoupper($method) === 'POST') {
            try {
                $response = $client->request(strtoupper($method), $url, [
                    'form_params' => $data,
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);

                return new ApiResponse($response);
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());

                return null;
            }
        }
    }

    /**
     * @param $entity
     * @param string $method
     * @return string
     * @throws \Exception
     */
    protected function getEndpoint($entity, $method = 'GET')
    {
        if (!isset($this->endpoints[$entity])) {
            throw new \Exception("Api endpoint not found.");
        }

        switch ($method) {
            case 'GET': case 'POST' : return $this->endpoints[$entity] . '?api_token=' . $this->api_token;
        }
    }
}
