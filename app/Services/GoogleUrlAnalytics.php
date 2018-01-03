<?php
namespace App\Services;

use App\Services\GoogleApi;

class GoogleUrlAnalytics
{
    protected $api;

    public function __construct($auth)
    {
        $this->api = new GoogleApi($auth, 'https://www.googleapis.com/urlshortener/v1/');
    }

    public function get($url){
        $data = $this->api->get('url?shortUrl=' . $url . '&projection=FULL');

        return [
            'short' => $data['id'],
            'long' => $data['longUrl'],
            'all_time' => $data['analytics']['allTime']['shortUrlClicks'],
            'month' => $data['analytics']['month']['shortUrlClicks'],
            'week' => $data['analytics']['week']['shortUrlClicks'],
            'day' => $data['analytics']['day']['shortUrlClicks'],
            'two_hours' => $data['analytics']['twoHours']['shortUrlClicks']
        ];
    }

}