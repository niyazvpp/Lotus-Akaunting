<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Akaunting
{

    private $url;

    private $username;

    private $password;

    public function __construct()
    {
        $this->url = 'https://app.akaunting.com/api/';
        $this->username  = env('AKAUNTING_USERNAME');
        $this->password  = env('AKAUNTING_PASSWORD');
    }

    public function get($endpoint, $params = [], $headers = [])
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders($headers)
            ->get($this->url . $endpoint, $params);

        return $this->handleResponse($response);
    }

    public function post($endpoint, $data = [], $headers = [])
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders($headers)
            ->post($this->url . $endpoint, $data);

        return $this->handleResponse($response);
    }

    public function put($endpoint, $data = [], $headers = [])
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders($headers)
            ->put($this->url . $endpoint, $data);

        return $this->handleResponse($response);
    }

    public function delete($endpoint, $data = [], $headers = [])
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders($headers)
            ->delete($this->url . $endpoint, $data);

        return $this->handleResponse($response);
    }

    private function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }
}
