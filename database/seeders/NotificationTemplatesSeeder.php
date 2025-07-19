<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification_templates = [
            [
                'template_code' => 'REVIEW_RECIEVED',
                'subject' => 'A new rating has been given',
                'email_file_name' => 'emails.review.created'
            ],
            [
                'template_code' => 'BankWireOrderProcessed',
                'subject' => 'Awaiting bank wire payment at [app_name]',
                'email_file_name' => 'emails.order.bankwire-order-processed'
            ],
            [
                'template_code' => 'OrderCreated',
                'subject' => 'Order Placed Successfully',
                'email_file_name' => 'emails.order.placed'
            ],
            [
                'template_code' => 'EnquirySubmitted',
                'subject' => 'Thank you for your Product Enquiry!',
                'email_file_name' => 'emails.enquiry.product-enquiry'
            ],
            [
                'template_code' => 'NewCustomerRegistration',
                'subject' => 'New Customer Registerd at [app_name]',
                'email_file_name' => 'emails.customer.new-register'
            ],
            [
                'template_code' => 'TwoFactorAuthMail',
                'subject' => 'TwoFactor Authentication from [app_name]',
                'email_file_name' => 'emails.customer.two-factor-auth'
            ],
            [
                'template_code' => 'OrderCancelled',
                'subject' => 'Order Cancelled',
                'email_file_name' => 'emails.order.cancelled'
            ],
            [
                'template_code' => 'OrderShipped',
                'subject' => 'Order Shipped Successfully',
                'email_file_name' => 'emails.order.order-shipped'
            ],
            [
                'template_code' => 'OrderDelivered',
                'subject' => 'Order Delivered Successfully',
                'email_file_name' => 'emails.order.order-delivered'
            ]
        ];
        //NotificationTemplate::insert($notification_templates);

        // insert data if not exists in table
        foreach ($notification_templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['template_code' => $template['template_code']],
                $template
            );
        }
    }
}
