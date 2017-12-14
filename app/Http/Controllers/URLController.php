<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateURLRequest;
use App\URL;
use App\GoogleAuth;
use Illuminate\Http\Request;
use App\Services\GoogleUrlShortener;

class URLController extends Controller
{

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

        $return = [];
        $service = new GoogleUrlShortener($auth);
        foreach($request->input('urls') as $url){
            $data = $service->shorten($url);

            $return[] = $data;
        }

        return response()->json(['data' => $return]);
    }
}
