<?php

namespace App\Http\Traits;

trait Responses {

    public function Success(string $msg = '')
    {
        return response(json_encode(['error' => 0, 'msg' => $msg]), 200);
    }

    public function SuccessConversion(string $amount, int $fromCache)
    {
        return response(json_encode(['error' => 0, 'amount' => $amount, 'fromCache' => $fromCache]), 200);
    }

    public function Error(string $msg = '')
    {
        return response(json_encode(['error' => 1, 'msg' => $msg]), 200);
    }

}
