<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ShopController extends Controller
{

    use ValidatesRequests;


    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {

        $this->validate($request, [
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'status' => 'required',
            'type' => 'required',
            'max_delivery_distance' => 'required',
        ]);


        Shop::create($request->all());

        return response()->noContent();
    }
}
