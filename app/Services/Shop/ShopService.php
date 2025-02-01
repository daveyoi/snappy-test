<?php

namespace App\Services\Shop;

use App\Models\Enums\ShopStatus;
use App\Models\Postcode;
use App\Models\Shop;

class ShopService
{
    /**
     * Distance in Miles
     */
    const nearDistance = 5;

    const metersToMilesConversion = 0.000621371192;


    /**
     * @param $postcode
     * @return mixed
     * @throws \Exception
     */
    public function getShopsNear($postcode): mixed  {
        $address = $this->getAddress($postcode);

        if ($address) {
            $box = self::boundingBox($address->latitude, $address->longitude, self::nearDistance);

            $shops = Shop::whereBetween('latitude', [$box['minLat'], $box['maxLat']])
                ->whereBetween('longitude', [$box['minLon'], $box['maxLon']])
                ->whereRaw('(ST_Distance_Sphere(point(longitude, latitude), point(?, ?))) <= ?', [
                    $address->longitude,
                    $address->latitude,
                    self::nearDistance / self::metersToMilesConversion
                ])->get();
        }

        return $shops ?? null;
    }

    /**
     * @param $postcode
     * @return null
     */
    public function getShopsCanDeliver($postcode) {
        $address = $this->getAddress($postcode);

        if ($address) {
            $shops = Shop::where('status', ShopStatus::OPEN)
                ->whereRaw(
                    '(ST_Distance_Sphere(point(longitude, latitude), point(?, ?))) <= max_delivery_distance / ?', [
                    $address->longitude,
                    $address->latitude,
                    self::metersToMilesConversion
                ])->get();
        }

        return $shops ?? null;
    }


    /**
     * @param $postcode
     * @return Postcode|null
     */
    private function getAddress($postcode): ?Postcode {
        [$outcode, $incode] = explode(" ", $postcode);

        if ($outcode && $incode) {
            $address = Postcode::where('out_code', $outcode)
                ->where('in_code', $incode)->first();
        }

        return $address ?? null;
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param $distance
     * @return array
     * @throws \Exception
     */
    private static function boundingBox($latitude, $longitude, $distance) {
        $latLimits = [deg2rad(-90), deg2rad(90)];
        $lonLimits = [deg2rad(-180), deg2rad(180)];

        $radLat = deg2rad($latitude);
        $radLon = deg2rad($longitude);

        if ($radLat < $latLimits[0] || $radLat > $latLimits[1]
            || $radLon < $lonLimits[0] || $radLon > $lonLimits[1]) {
            throw new \Exception("Invalid Argument");
        }

        // Angular distance in radians on a great circle,
        // using Earth's radius in miles.
        $angular = $distance / 3958.762079;

        $minLat = $radLat - $angular;
        $maxLat = $radLat + $angular;

        if ($minLat > $latLimits[0] && $maxLat < $latLimits[1]) {
            $deltaLon = asin(sin($angular) / cos($radLat));
            $minLon = $radLon - $deltaLon;

            if ($minLon < $lonLimits[0]) {
                $minLon += 2 * pi();
            }

            $maxLon = $radLon + $deltaLon;

            if ($maxLon > $lonLimits[1]) {
                $maxLon -= 2 * pi();
            }
        } else {
            // A pole is contained within the distance.
            $minLat = max($minLat, $latLimits[0]);
            $maxLat = min($maxLat, $latLimits[1]);
            $minLon = $lonLimits[0];
            $maxLon = $lonLimits[1];
        }

        return [
            'minLat' => rad2deg($minLat),
            'minLon' => rad2deg($minLon),
            'maxLat' => rad2deg($maxLat),
            'maxLon' => rad2deg($maxLon),
        ];
    }
}
