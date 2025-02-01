<?php

namespace App\Models;

use App\Models\Enums\ShopStatus;
use App\Models\Enums\ShopType;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'type',
        'max_delivery_distance'
    ];

    protected $casts = [
        'status' => ShopStatus::class,
        'type' => ShopType::class
    ];

}
