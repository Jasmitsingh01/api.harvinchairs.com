<?php

namespace App\Payments;

use Exception;
use App\Exceptions\MarvelException;
use App\Payments\PaymentInterface;
use App\Payments\Base;
use Mollie\Laravel\Facades\Mollie as MollieFacade;

class Mollie extends Base implements PaymentInterface
{
  public function getIntent($data)
  {
    try {
      extract($data);
      $payment = MollieFacade::api()->payments->create([
        "amount" => [
          "currency" => $this->currency,
          "value" => number_format($amount, 2) // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order From " . config('app.name'),
        "redirectUrl" => config('shop.mollie.redirect_url'),
      ]);
      return ['redirect_url' => $payment->getCheckoutUrl(), 'payment_id' => $payment->id, 'is_redirect' => true];
    } catch (Exception $e) {
      throw new MarvelException(SOMETHING_WENT_WRONG_WITH_PAYMENT);
    }
  }

  public function verify($paymentId)
  {
    try {
      $payment = MollieFacade::api()->payments()->get($paymentId);
      return $payment->isPaid();
    } catch (Exception $e) {
      throw new MarvelException(SOMETHING_WENT_WRONG_WITH_PAYMENT);
    }
  }
}
