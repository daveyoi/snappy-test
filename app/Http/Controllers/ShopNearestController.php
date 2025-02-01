<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopsResource;
use App\Services\Shop\ShopService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopNearestController extends Controller
{

    /**
     * @param ShopService $shopService
     */
    public function __construct(protected ShopService $shopService) {}


    /**
     * @param $postcode
     * @return array|AnonymousResourceCollection
     * @throws \Exception
     */
    public function index($postcode): AnonymousResourceCollection|array {
        $shops = $this->shopService->getShopsNear($postcode);
        return $shops ? ShopsResource::collection($shops) : [];
    }
}
