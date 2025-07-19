<?php

namespace Database\Seeders;

use App\Models\FaqType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faqtype = [
            [
                'name'           => 'Order Tracking & Delivery',
                'subline'          => 'Questions about tracking and receiving your order.',
                'icon'       => 'Order_Tracking_Delivery.png',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name'           => 'Payment & Invoice',
                'subline'          => 'Assistance with payment-related inquiries.',
                'icon'       => 'Payment_Invoice.png',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name'           => 'Account Management',
                'subline'          => 'Guidance for managing your account effectively.',
                'icon'       => 'Account_Management.png',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name'           => 'Warranty Support',
                'subline'          => 'Solutions for warranty-related questions.',
                'icon'       => 'Warranty_Support.png',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name'           => 'Damaged or Defective Item',
                'subline'          => 'Help for concerns with flawed or harmed products.',
                'icon'       => 'Damaged_Defective_Item.png',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ];

        FaqType::insert($faqtype);
    }
}
