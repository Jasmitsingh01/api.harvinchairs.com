<?php


namespace App\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Database\Models\PaymentMethod;

class PaymentMethods implements ShouldQueue
{
    /**
     * @var PaymentMethod
     */

    public $payment_methods;

    /**
     * Create a new event instance.
     *
     * @param PaymentMethod $payment_methods
     */
    public function __construct(PaymentMethod $payment_methods)
    {
        $this->payment_methods = $payment_methods;
    }
}
