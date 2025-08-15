<?php

namespace App\Providers;

use App\Events\SendMail;
use App\Events\OrderCreated;
use App\Events\OrderReceived;
use App\Events\PaymentFailed;
use App\Events\ReviewCreated;
use App\Events\OrderCancelled;
use App\Events\OrderProcessed;
use App\Events\PaymentMethods;
use App\Events\PaymentSuccess;
use App\Events\RefundApproved;
use App\Events\QuestionAnswered;
use App\Events\SendNotification;
use App\Listeners\RatingRemoved;
use App\Listeners\SendMailFired;
use App\Events\EnquirySubmittedEvent;
use App\Events\BankWireOrderProcessed;
use App\Listeners\SendNotificationFired;
use App\Listeners\CheckAndSetDefaultCard;
use App\Listeners\SendOrderCancelledAdminNotification;
use App\Listeners\SendReviewNotification;
use App\Listeners\ProductInventoryRestore;
use App\Listeners\SendEnquiryNotification;
use App\Listeners\ProductInventoryDecrement;
use App\Listeners\SendBankWireOrderNotification;
use App\Listeners\SendOrderCreationNotification;
use App\Listeners\SendOrderReceivedNotification;
use App\Listeners\SendPaymentFailedNotification;
use App\Listeners\SendOrderCancelledNotification;
use App\Listeners\SendPaymentSuccessNotification;
use App\Listeners\SendQuestionAnsweredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        QuestionAnswered::class => [
            SendQuestionAnsweredNotification::class
        ],
        ReviewCreated::class => [
            SendReviewNotification::class
        ],
        OrderCreated::class => [
            SendOrderCreationNotification::class,
        ],
        OrderReceived::class => [
            SendOrderReceivedNotification::class
        ],
        OrderProcessed::class => [
            ProductInventoryDecrement::class,
        ],
        OrderCancelled::class => [
            ProductInventoryRestore::class,
            SendOrderCancelledNotification::class,
            SendOrderCancelledAdminNotification::class
        ],
        RefundApproved::class => [
            RatingRemoved::class
        ],
        PaymentSuccess::class => [
            SendPaymentSuccessNotification::class
        ],
        PaymentFailed::class => [
            SendPaymentFailedNotification::class
        ],
        PaymentMethods::class => [
            CheckAndSetDefaultCard::class
        ],
        SendNotification::class => [
            SendNotificationFired::class
        ],
        EnquirySubmittedEvent::class => [
            SendEnquiryNotification::class,
        ],
        BankWireOrderProcessed::class => [
            SendBankWireOrderNotification::class,
        ],
        SendMail::class => [
            SendMailFired::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
