<?php

namespace App\Traits;

use App\Database\Models\Wallet;
use App\Database\Models\Settings;
use Illuminate\Support\Facades\DB;
use App\Database\Models\Address;
use App\Models\OrderStatusHistory;

trait OrderStatusHistoryTrait
{

  /**
   * Convert currency to wallet points
   */
    public static function storeOrderStatusHistory($order)
    {
        OrderStatusHistory::create([
            "order_id" => $order->id,
            "status" => $order->order_status,
            "payment_status" => $order->payment_status
        ]);
        return true;
    }
}
