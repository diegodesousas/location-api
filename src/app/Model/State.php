<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'name',
        'abbreviation'
    ];
}