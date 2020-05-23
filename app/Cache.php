<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cache extends Model
{
    protected $fillable = [
        'from',
        'to',
        'rate',
    ];
}
