<?php

namespace App\Jobs;

use App\Libraries\ElasticSearch\ElasticSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ESProductIndex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productId;

    public function __construct(int $productId=0)
    {
        $this->productId = $productId;
    }

    public function handle()
    {
        //ElasticSearch::instance()->productBulkCreate();
        ElasticSearch::instance()->productCreate($this->productId);
    }
}
