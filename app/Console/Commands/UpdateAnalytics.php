<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Collection;
use App\Services\GoogleUrlAnalytics;

use Cache;
use Carbon\Carbon;

class UpdateAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update URL Analytics for all Google Auths';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collections = Collection::all();

        foreach($collections as $collection){
            $service = new GoogleUrlAnalytics($collection->auth);
            foreach($collection->urls as $url){
                $data = $service->get($url->short_url);

                $url->update($data);
            }
        }

        Cache::forever('analytics_update', Carbon::now());
    }
}
