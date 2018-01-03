<?php
namespace App\Services;

use App\Services\GoogleApi;

class GoogleUrlHistory
{
    protected $api;

    public function __construct($auth)
    {
        $this->api = new GoogleApi($auth, 'https://www.googleapis.com/urlshortener/v1/');
    }

    public function get(){
        $pageToken = null;
        $x = 0;
        while(true){
            if($pageToken !== null){
                $url = 'url/history?projection=FULL&start-token=' . $pageToken . '&key=AIzaSyBrrCQogJHjHyo2EKj4QE0SraBTPqRcKwo';
            }else{
                $url = 'url/history?projection=FULL';
            }
            $data = $this->api->get($url);

            dump($data);

            if(isset($data['nextPageToken'])){
                $pageToken = $data['nextPageToken'];
            }else{
                dd('yaaa!');
                break;
            }

            $x++;

            if($x > 20){
                dd('oops');
            }
        }

        return [
            'short' => $data['id'],
            'long' => $data['longUrl']
        ];
    }

}