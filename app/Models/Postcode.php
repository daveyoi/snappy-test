<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    protected $table = 'postcodes';

    protected $fillable = [
        'out_code',
        'in_code',
        'latitude',
        'longitude'
    ];

}
