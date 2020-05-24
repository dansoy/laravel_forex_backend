<?php

namespace App\Jobs;

use App\Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cache;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cache = Cache::find($this->cache->id);

        $time = now()->subSeconds(config('forex.cache_time'));

        if($cache && $time->greaterThanOrEqualTo($cache->updated_at))
        {
            $cache->delete();
        }
    }
}
