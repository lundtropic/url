<?php
namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Google_Client;

class GoogleApi
{
    protected $auth;
    protected $base_uri;
    protected $client;

    public function __construct($auth, $base_uri)
    {
        $this->auth = $auth;
        $this->base_uri = $base_uri;

        $this->createClient();
    }

    public function get($url)
    {
        $this->refreshToken();

//        try {
            $response = $this->client->get($url);
//        } catch (RequestException $e) {
//            dump($url);
//            dump(Psr7\str($e->getRequest()));
//            if ($e->hasResponse()) {
//                dump(Psr7\str($e->getResponse()));
//            }
//
//            die();
//        }

        $body = $response->getBody();
        $stringBody = (string) $body;
        $data = json_decode($stringBody, true);

        return $data;
    }

    public function post($url)
    {
        $this->refreshToken();

//        try {
            $response = $this->client->post('url', [
                'json' => [
                    'longUrl' => $url
                ]
            ]);
//        } catch (RequestException $e) {
//            var_dump($url);
//            var_dump(Psr7\str($e->getRequest()));
//            if ($e->hasResponse()) {
//                var_dump(Psr7\str($e->getResponse()));
//            }
//
//            die();
//        }

        $body = $response->getBody();
        $stringBody = (string) $body;
        $data = json_decode($stringBody, true);

        return $data;
    }


    private function createClient()
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->auth->token
            ]
        ]);
    }

    private function refreshToken()
    {
        if(Carbon::now() >= $this->auth->expiry_time){
            $client = new Google_Client;
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->refreshToken($this->auth->refresh_token);
            $client->setAccessType('offline');

            $token = $client->getAccessToken();

            $this->auth->token = $token['access_token'];
            $this->auth->save();

            $this->createClient();
        }
    }
}