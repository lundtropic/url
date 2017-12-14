<?php
namespace App\Services;

use App\Services\GoogleApi;

use GuzzleHttp\Client;

class GoogleUrlShortener
{
    protected $api;

    public function __construct($auth)
    {
        $this->api = new GoogleApi($auth, 'https://www.googleapis.com/urlshortener/v1/');
    }

    public function shorten($url){
        $data = $this->api->get($url);

        return [
            'short' => $data['id'],
            'long' => $data['longUrl']
        ];
    }

}