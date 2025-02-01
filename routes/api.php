<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopDeliveryController;
use App\Http\Controllers\ShopNearestController;
use Illuminate\Support\Facades\Route;


Route::post('/shop', [ShopController::class, 'store']);

Route::get('/shop/nearest/{postcode}', [ShopNearestController::class, 'index']);
Route::get('/shop/deliver/{postcode}', [ShopDeliveryController::class, 'index']);
