<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use App\Database\Models\Order;
use App\Database\Models\Settings;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Facades\Payment;
use App\Models\OrderPayment;

trait PaymentStatusManagerWithOrderTrait
{
    use OrderStatusManagerWithPaymentTrait, PaymentTrait;


    /**
     * stripe
     *
     * @param  mixed $order
     * @param  mixed $request
     * @param  mixed $settings
     * @return void
     */
    public function stripe($order, $request, $settings): void
    {
        try {
            $chosen_intent = '';
            // for single gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($settings->options['paymentGateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $intent_secret = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['client_secret'] : null;
            $payment_intent_id = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;

            if (isset($intent_secret) && isset($payment_intent_id)) {
                $retrieved_intent = Payment::retrievePaymentIntent($payment_intent_id);
                $retrieved_intent_status = $retrieved_intent->status;

                switch ($retrieved_intent_status) {
                    case 'succeeded':
                        $this->paymentSuccess($order);
                        break;

                    case 'requires_action':
                        $this->paymentProcessing($order);
                        break;

                    case 'requires_payment_method':
                        $this->paymentFailed($order);
                        break;
                }
            }
        } catch (Exception $e) {
            throw new \Exception(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * Status change for paypal
     *
     * @throws Exception
     */
    public function paypal(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for single gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($settings->options['paymentGateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $payment = Payment::verify($paymentId);
                if ($payment) {
                    $order_payment['order_id'] = $order->id;
                    $order_payment['order_reference'] = $order->tracking_number;
                    // $order_payment['currency'] = $payment['purchase_units'][0]['captures'][0]['amount']['currency_code'];
                    $order_payment['amount'] = $order->amount;
                    $order_payment['payment_method'] = "paypal";
                    $order_payment['currency'] ="USD";
                    $order_payment['transaction_id'] = $payment['id'];
                    $data = OrderPayment::create($order_payment);
                    $paymentStatus = $payment["status"];
                    switch (strtolower($paymentStatus)) {
                        case "completed":
                            $this->paymentSuccess($order);
                            break;
                        case "payer_action_required":
                            $this->paymentProcessing($order);
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            dd($e);
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for razorpay
     *
     * @throws Exception
     */
    public function razorpay(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for single gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($settings->options['paymentGateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $payment = Payment::verify($paymentId);
                if ($payment) {
                    $paymentVerify = Payment::paymentVerify($paymentId);
                    $order_payment['order_id'] = $order->id;
                    $order_payment['order_reference'] = $order->tracking_number;
                    // $order_payment['currency'] = $payment['purchase_units'][0]['captures'][0]['amount']['currency_code'];
                    $order_payment['amount'] = $order->amount;
                    $order_payment['payment_method'] = "RAZORPAY";
                    $order_payment['currency'] ="INR";
                    $order_payment['transaction_id'] = $paymentId;
                    if(isset($paymentVerify->items[0]->method)){
                        $order_payment['payment_type'] = $paymentVerify->items[0]->method;
                    }
                    $data = OrderPayment::create($order_payment);
                    // Order payment type update start

                    if(isset($paymentVerify->items[0]->method)){
                        Order::where('id',$order->id)->update(['payment_type' => $paymentVerify->items[0]->method]);
                    }
                    //Order::where('id',$order->id)->update(['payment_type' => $payment->method]);
                    // Order payment type update end
                    switch (strtolower($payment->status)) {
                        case "paid":
                            $this->paymentSuccess($order);
                            break;
                        case "attempted":
                            $this->paymentProcessing($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }


    /**
     * Update DB status after payment success
     *
     * @param $order
     * @return void
     */
    protected function paymentSuccess($order): void
    {
        $order->order_status = OrderStatus::PROCESSING;
        $order->payment_status = PaymentStatus::SUCCESS;
        $order->save();
        try {
            $children = json_decode($order->children);
        } catch (\Throwable $th) {
            $children = $order->children;
        }
        if (is_array($children) && count($children)) {
            foreach ($order->children as $child_order) {
                $child_order->order_status = OrderStatus::PROCESSING;
                $child_order->payment_status = PaymentStatus::SUCCESS;
                $child_order->save();
            }
        }
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->getRawOriginal('payment_status'));
    }

    /**
     * Update DB status after payment processing
     *
     * @param $order
     * @return void
     */
    protected function paymentProcessing($order): void
    {
        $order->order_status = OrderStatus::PROCESSING;
        $order->payment_status = PaymentStatus::PROCESSING;
        $order->save();
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->getRawOriginal('payment_status'));
    }

    /**
     * Update DB status after payment failed
     *
     * @param $order
     * @return void
     */
    protected function paymentFailed($order): void
    {
        $order->order_status = OrderStatus::FAILED;
        $order->payment_status = PaymentStatus::FAILED;
        $order->save();
        $this->orderStatusManagementOnPayment($order, $order->order_status,$order->getRawOriginal('payment_status'));
    }
}
