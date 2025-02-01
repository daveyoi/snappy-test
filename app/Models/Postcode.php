<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    use HasFactory;

    protected $table = 'postcodes';

    protected $fillable = [
        'out_code',
        'in_code',
        'latitude',
        'longitude'
    ];

}
