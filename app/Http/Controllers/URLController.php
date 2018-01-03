<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateURLRequest;
use App\Services\GoogleUrlAnalytics;
use App\URL;
use App\GoogleAuth;
use Illuminate\Http\Request;
use App\Services\GoogleUrlShortener;
use App\Collection;
use Carbon\Carbon;

class URLController extends Controller
{
    public function index()
    {
        $collections = \App\Collection::with('urls')->orderByDesc('created_at')->get();

        return response()->json(['data' => $collections]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateURLRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateURLRequest $request)
    {
        $auth = GoogleAuth::where('google_id', request('auth'))
            ->firstOrFail();

        $collectionName = request('name') !== null && trim(request('name') !== '')
            ? trim(request('name'))
            : Carbon::now()->format('Y-m-d H:i:s');

        $collection = new Collection();
        $collection->name = $collectionName;
        $collection->google_auth_id = $auth->id;
        $collection->save();

        $return = [];

        if(request('type') === 'new'){
            $service = new GoogleUrlShortener($auth);
            foreach($request->input('urls') as $url){
                $data = $service->shorten($url);

                $url = new Url();
                $url->collection_id = $collection->id;
                $url->short_url = $data['short'];
                $url->long_url = $data['long'];
                $url->save();

                $return[] = $data;
            }
        }else{
            $service = new GoogleUrlAnalytics($auth);
            foreach($request->input('urls') as $url){
                $data = $service->get($url);

                $url = new Url();
                $url->collection_id = $collection->id;
                $url->short_url = $data['short'];
                $url->long_url = $data['long'];
                $url->all_time = $data['all_time'];
                $url->month = $data['month'];
                $url->week = $data['week'];
                $url->day = $data['day'];
                $url->two_hours = $data['two_hours'];
                $url->save();

                $return[] = $data;
            }
        }


        return response()->json(['data' => $return]);
    }
}
