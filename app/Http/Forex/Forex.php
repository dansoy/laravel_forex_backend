<?php

namespace App\Http\Forex;

use App\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Forex {

    private $from;

    private $to;

    private $rate;

    private $fromCache;

    private $cache;

    public function __construct(string $from = "", string $to = "")
    {
        if($from != "" && $to != "")
        {
            //Initialize
            $this->from = $from;
            $this->to = $to;

            if($this->from != $this->to){
                //Get Rate from Cache
                $this->rate = $this->getRateFromCache();

                //if doesn't exists in cache or expired Get Rate from API
                if($this->rate == 0)
                {
                    $this->rate = $this->getRateFromAPI();
                    $this->updateCache();
                }
            }
            else{
                $this->rate = 1;
                $this->fromCache = 0;
            }
        }

    }

    /**
     * Check if currency is allowed.
     *
     * @return string
     */
    public function isCurrencyAllowed()
    {
        if(!in_array($this->from, config('forex.allowed_currencies')))
        {
            return $this->from;
        }
        elseif(!in_array($this->to, config('forex.allowed_currencies')))
        {
            return $this->to;
        }
        return 0;
    }

    /**
     * Convert currency.
     *
     * @param  string  $amount
     * @return string
     */
    public function convert($amount)
    {
        $result = $amount * $this->rate;
        return number_format($result, 2, '.', ',');
    }

    /**
     * Return 1 if from cache. Otherwise, return 0.
     *
     * @return int
     */
    public function existsInCache()
    {
        return $this->fromCache;
    }

    /**
     * Clear cache.
     *
     */
    static function clearCache()
    {
        Cache::truncate();
    }

    /**
     * Get exchange rate from db. if not exists return 0.
     *
     * @return float
     */
    private function getRateFromCache()
    {
        //init
        $this->fromCache = 0;

        //Check database if exchange exists
        $this->cache = Cache::where([
                            ['from', $this->from], ['to', $this->to],
                        ])->orWhere([
                            ['from', $this->to], ['to', $this->from],
                        ])->first();

        //if exists, check expiry. if expired delete
        $time = Carbon::now()->startOfMinute()->subSeconds(config('forex.cache_time'));

        if($this->cache && $time->lessThan($this->cache->updated_at))
        {
            $this->fromCache = 1;
            return $this->cache->rate;
        }

        return 0;
    }

    /**
     * Update cache.
     *
     */
    private function updateCache()
    {
        if($this->cache)
        {
            $newRate = $this->cache->from == $this->from ? $this->rate : 1/$this->rate;
            $this->cache->update(['rate' => $newRate]);
        }
        else
        {
            $this->cache = Cache::create([
                'from' => $this->from,
                'to' => $this->to,
                'rate' => $this->rate,
                ]);
        }
    }

    /**
     * Get exchange rate from API.
     *
     * @return float
     */
    private function getRateFromAPI()
    {
        $response = Http::get(config('forex.api_url').'?base='.$this->from.'&symbols='.$this->to);

        if($response->successful())
        {
            return $response['rates'][$this->to];
        }

        return 0;
    }
}
