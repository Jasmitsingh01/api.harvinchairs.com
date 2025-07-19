<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $setting = [

            ['title'=> 'Default Per Page Limit','varname'=> 'DEFAULT_RECORD_LIMIT',
            'description'=> 'Default no. of records displayed per page in admin.','value'=> '20',
            'option_order'=> '9','var_type'=> 'list','var_opt_val'=> '5,10,20,30,50,75,100,200,500','var_opt_display'=> '5,10,20,30,50,75,100,200,500'
            ],
            ['title'=> 'Admin name','varname'=> 'ADMIN_NAME',
            'description'=> 'Used as from name in emails.','value'=> 'Harvin Chairs',
            'option_order'=> '4','var_type'=> 'text','var_opt_val'=> '','var_opt_display'=> ''
            ],
            [
                'title' => 'Home Page Title',
                'varname' => 'HOME_PAGE_TITLE',
                'description' => 'This title will show on the home page.',
                'value' => 'Harvin Chairs',
                'option_order' => '5',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Home Page Meta Keywords',
                'varname' => 'HOME_PAGE_META_KEYWORDS',
                'description' => 'This meta keywords will show on the home page.',
                'value' => 'Harvin Chairs',
                'option_order' => '60',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Home Page Meta Description',
                'varname' => 'HOME_PAGE_META_DESCRIPTION',
                'description' => 'This meta description will show on the home page.',
                'value' => 'Harvin Chairs',
                'option_order' => '12',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'CGST max %',
                'varname' => 'CGST_MAX_PERCENTAGE',
                'description' => 'Max percentage of GST.',
                'value' => '18',
                'option_order' => '10',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SGST max %',
                'varname' => 'SGST_MAX_PERCENTAGE',
                'description' => 'Max percentage of GST.',
                'value' => '18',
                'option_order' => '10',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Maximum Cart Item Limit',
                'varname' => 'MAX_CART_ITEM_LIMIT',
                'description' => 'Max cart item Limit for create single order',
                'value' => '10',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Maximum Amount Per Single Order',
                'varname' => 'MAX_AMOUNT_PER_ORDER',
                'description' => 'Max Amount Limit for create single order',
                'value' => '250000',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Order Cancellation Timeframe',
                'varname' => 'ORDER_CANCEL_TIMEFRAME',
                'description' => 'Max Limit for cancel order',
                'value' => '3',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'Eligible Order Status for Cancellation',
                'varname' => 'ELIGIBAL_ORDER_STATUS_FOR_CANCELLATION',
                'description' => 'order status eligible for cancel order',
                'value' => 'Order Shipped',
                'option_order' => '11',
                'var_type' => 'select','var_opt_val' => 'Order Pending,Order Processing,Order Shipped','var_opt_display' => ''
            ],
            [
                'title' => 'Cancellation Order Email Receiver',
                'varname' => 'CANCELLATION_ORDER_EMAIL_RECEIVER',
                'description' => 'receive email of order cancel',
                'value' => 'store_owner@demo.com',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP HOST',
                'varname' => 'MAIL_HOST',
                'description' => 'smtp settings',
                'value' => 'smtp.mailtrap.io',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP PORT',
                'varname' => 'MAIL_PORT',
                'description' => 'smtp settings smtp port',
                'value' => '587',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP USERNAME',
                'varname' => 'MAIL_USERNAME',
                'description' => 'smtp settings smtp username',
                'value' => 'd7s8d7s98d',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP PASSWORD',
                'varname' => 'MAIL_PASSWORD',
                'description' => 'smtp settings smtp password',
                'value' => 'd7s8d7s98d',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP ENCRYPTION',
                'varname' => 'MAIL_ENCRYPTION',
                'description' => 'smtp settings smtp encryption',
                'value' => 'tls',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP From Address',
                'varname' => 'MAIL_FROM_ADDRESS',
                'description' => 'smtp settings smtp from address',
                'value' => 'noreply@example.com',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'SMTP settings: SMTP From Name',
                'varname' => 'MAIL_FROM_NAME',
                'description' => 'smtp settings smtp from name',
                'value' => 'Example',
                'option_order' => '11',
                'var_type' => 'text','var_opt_val' => '','var_opt_display' => ''
            ],
            [
                'title' => 'GST %',
                'varname' => 'GST_PERCENTAGE',
                'description' => 'GST percentage',
                'value' => '18',
                'option_order' => '10',
                'var_type' => 'textarea','var_opt_val' => '','var_opt_display' => ''
            ],


        ];

    	// DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('system_configurations')->truncate();
        // DB::table('system_configurations')->insert($setting);
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // check if already exist than not insert and if not exist than insert
        foreach ($setting as $key => $value) {
            $exist = DB::table('system_configurations')->where('varname', $value['varname'])->first();
            if (!$exist) {
                DB::table('system_configurations')->insert($value);
            }
        }
    }
}
