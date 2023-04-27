<?php

namespace App\Jobs;

use App\Libraries\ElasticSearch\ElasticSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ESRestaurantIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $restaurantId;

    public function __construct(int $restaurantId=0)
    {
        $this->restaurantId = $restaurantId;
    }

    public function handle()
    {
        //ElasticSearch::instance()->restaurantBulkCreate();
        ElasticSearch::instance()->restaurantCreate($this->restaurantId);
        //$a=ElasticSearch::instance()->restaurant($this->restaurantId);
        //$a=ElasticSearch::instance()->restaurantSearch('bir',0);
//        if ($a['found'])
//        dd($a['_source']);
    }
}
