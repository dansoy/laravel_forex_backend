<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Forex\Forex;
use App\Http\Traits\Responses;

class ForexController extends Controller
{
    use Responses;
    /**
     * Convert Currency.
     *
     * @param  string  $amount
     * @param  string  $from
     * @param  string  $to
     * @return \Illuminate\Http\Response
     */
    public function convert(string $amount, string $from, string $to)
    {
        $forex = new Forex($from, $to);

        $currency = $forex->isCurrencyAllowed();
        if($currency){
            $msg = "currency code " . $currency . " not supported";
            return $this->Error($msg);
        }

        return $this->SuccessConversion($forex->convert($amount), $forex->existsInCache());
    }

    /**
     * Show creator.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $msg = "API written by " . config('forex.created_by');
        return $this->Success($msg);
    }

    /**
     * Clear Cache.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCache()
    {
        Forex::clearCache();
        return $this->Success("OK");
    }

}
