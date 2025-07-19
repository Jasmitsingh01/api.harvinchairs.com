<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Database\Models\Settings;
use App\Exceptions\MarvelException;
use App\Database\Repositories\PaymentIntentRepository;

class PaymentIntentController extends CoreController
{
    public $repository;
    public $settings;

    public function __construct(PaymentIntentRepository $repository)
    {
        $this->repository = $repository;
        $this->settings = Settings::first();
    }


    /**
     * getPaymentIntent
     * 
     * This function create the payment intent for the payment & store that into database with related to that order.
     * So that, if the intent was kept track in any case for current or future payment.
     *
     * @param  mixed $request
     * @return void
     */
    public function getPaymentIntent(Request $request)
    {
        try {
            return $this->repository->getPaymentIntent($request, $this->settings);
        } catch (Exception $e) {
            throw new MarvelException();
        }
    }
}
