<?php

use App\Models\Postcode;
use App\Models\Shop;
use Illuminate\Testing\Fluent\AssertableJson;

uses()->group('shop');

it('can add a shop', function () {

    $payload = [
        'name' => "Test Shop",
        'latitude' => 51.23232,
        'longitude' => -123.456,
        'status' => 'open',
        'type' => 'shop',
        'max_delivery_distance' => 4
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->post("api/shop", $payload);

    $response->assertStatus(204);
});

it('returns validation data incomplete shop data is passed', function () {
    $payload = [
        'name' => "Test Shop"
    ];


    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->post("api/shop", $payload);

    $response->assertStatus(422);
});

it('can get nearest shops to a postcode', function () {

    Postcode::create([
        'out_code' => 'AL7',
        'in_code' => '1HN',
        'latitude' => 51.793768,
        'longitude' => -0.173443,
    ]);

    Shop::create([
        'name' => 'Megans Welwyn',
        'latitude' => 51.801505,
        'longitude' => -0.207171,
        'status' => 'open',
        'type' => 'restaurant',
        'max_delivery_distance' => 3
    ]);

    Shop::create([
        'name' => 'Postino',
        'latitude' => 51.804121,
        'longitude' => -0.215369,
        'status' => 'open',
        'type' => 'takeaway',
        'max_delivery_distance' => 4
    ]);

    Shop::create([
        'name' => 'IBOs Bar & Restaurant',
        'latitude' => 51.94829104,
        'longitude' => -0.2787107,
        'status' => 'open',
        'type' => 'restaurant',
        'max_delivery_distance' => 4
    ]);

    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->get("api/shop/nearest/AL7 1HN");


    $response->assertJson(fn (AssertableJson $json) =>
        $json->count('data', 2)->etc()
    );
});


it('can get shops who can deliver to a certain postcode', function () {

    Postcode::create([
        'out_code' => 'AL7',
        'in_code' => '1HN',
        'latitude' => 51.793768,
        'longitude' => -0.173443,
    ]);

    Shop::create([
        'name' => 'Megans Welwyn',
        'latitude' => 51.801505,
        'longitude' => -0.207171,
        'status' => 'open',
        'type' => 'restaurant',
        'max_delivery_distance' => 3
    ]);

    Shop::create([
        'name' => 'Postino',
        'latitude' => 51.804121,
        'longitude' => -0.215369,
        'status' => 'closed',
        'type' => 'takeaway',
        'max_delivery_distance' => 4
    ]);

    Shop::create([
        'name' => 'IBOs Bar & Restaurant',
        'latitude' => 51.94829104,
        'longitude' => -0.2787107,
        'status' => 'open',
        'type' => 'restaurant',
        'max_delivery_distance' => 5
    ]);

    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->get("api/shop/deliver/AL7 1HN");

    $response->assertJson(fn (AssertableJson $json) =>
        $json->count('data', 1)->etc()
    );
});
