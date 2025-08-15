<?php

namespace App\Traits;

use App\Database\Models\Wallet;
use App\Database\Models\Settings;
use Illuminate\Support\Facades\DB;
use App\Database\Models\Address;
trait ShippingTrait
{

  /**
   * Convert currency to wallet points
   */
    public static function getFinalShippingCharge($totAmt, $userId, $addId, $freeShippingThresholdAmount)
    {
        $freeShipAmt = null;
        $shipAmt = null;
        $shippingCharge = Address::
        where('customer_id', $userId)
        ->where('address.id', $addId)
        ->leftJoin('zipcodes', 'address.zipcode', '=', 'zipcodes.zip_code')
        ->leftJoin('zones', 'address.zone_id', '=', 'zones.id')
        ->select('address.*','zones.*', 'zipcodes.amount AS zip_amount')
        ->orderBy('zip_amount', 'asc')
        ->orderBy('zones.Ship_Amt', 'asc')
        ->first();
        $freeShipAmt = $shippingCharge->zone->MinShip_Amt ?? $freeShippingThresholdAmount;
        $shipAmt = 0;
        if(isset($shippingCharge->zip_amount)){
            $shipAmt = $shippingCharge->zip_amount;
        }elseif(isset($shippingCharge->zone->Ship_Amt)){
            $shipAmt = $shippingCharge->zone->Ship_Amt;
        }
        // $shipAmt = $shippingCharge->zip_amount ?? $shippingCharge->zone->Ship_Amt;
        if ($totAmt >= $freeShipAmt) {
            $shipAmt = 0;
        }
        return $shipAmt ?? 0;
    }
}
