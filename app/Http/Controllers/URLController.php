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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = URL::paginate();

        return response()->json($url);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $return = [];
        $service = new GoogleUrlShortener($auth);
        foreach($request->input('urls') as $url){
            $data = $service->shorten($url);

            $return[] = $data;
        }

        return response()->json(['data' => $return]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\URL  $uRL
     * @return \Illuminate\Http\Response
     */
    public function show(URL $uRL)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\URL  $uRL
     * @return \Illuminate\Http\Response
     */
    public function edit(URL $uRL)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\URL  $uRL
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, URL $uRL)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\URL  $uRL
     * @return \Illuminate\Http\Response
     */
    public function destroy(URL $uRL)
    {
        //
    }
}
