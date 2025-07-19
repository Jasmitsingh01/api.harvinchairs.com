<?php


namespace App\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Database\Models\Order;

class PaymentSuccess implements ShouldQueue
{
    /**
     * @var Order
     */

    public $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
