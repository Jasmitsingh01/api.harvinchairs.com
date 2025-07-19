<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'category_create',
            ],
            [
                'id'    => 18,
                'title' => 'category_edit',
            ],
            [
                'id'    => 19,
                'title' => 'category_show',
            ],
            [
                'id'    => 20,
                'title' => 'category_delete',
            ],
            [
                'id'    => 21,
                'title' => 'category_access',
            ],
            [
                'id'    => 22,
                'title' => 'feature_create',
            ],
            [
                'id'    => 23,
                'title' => 'feature_edit',
            ],
            [
                'id'    => 24,
                'title' => 'feature_show',
            ],
            [
                'id'    => 25,
                'title' => 'feature_delete',
            ],
            [
                'id'    => 26,
                'title' => 'feature_access',
            ],
            [
                'id'    => 27,
                'title' => 'shop_create',
            ],
            [
                'id'    => 28,
                'title' => 'shop_edit',
            ],
            [
                'id'    => 29,
                'title' => 'shop_show',
            ],
            [
                'id'    => 30,
                'title' => 'shop_delete',
            ],
            [
                'id'    => 31,
                'title' => 'shop_access',
            ],
            [
                'id'    => 32,
                'title' => 'attribute_create',
            ],
            [
                'id'    => 33,
                'title' => 'attribute_edit',
            ],
            [
                'id'    => 34,
                'title' => 'attribute_show',
            ],
            [
                'id'    => 35,
                'title' => 'attribute_delete',
            ],
            [
                'id'    => 36,
                'title' => 'attribute_access',
            ],
            [
                'id'    => 37,
                'title' => 'product_create',
            ],
            [
                'id'    => 38,
                'title' => 'product_edit',
            ],
            [
                'id'    => 39,
                'title' => 'product_show',
            ],
            [
                'id'    => 40,
                'title' => 'product_delete',
            ],
            [
                'id'    => 41,
                'title' => 'product_access',
            ],
            [
                'id'    => 42,
                'title' => 'product_feature_create',
            ],
            [
                'id'    => 43,
                'title' => 'product_feature_edit',
            ],
            [
                'id'    => 44,
                'title' => 'product_feature_show',
            ],
            [
                'id'    => 45,
                'title' => 'product_feature_delete',
            ],
            [
                'id'    => 46,
                'title' => 'product_feature_access',
            ],
            [
                'id'    => 47,
                'title' => 'product_attribute_create',
            ],
            [
                'id'    => 48,
                'title' => 'product_attribute_edit',
            ],
            [
                'id'    => 49,
                'title' => 'product_attribute_show',
            ],
            [
                'id'    => 50,
                'title' => 'product_attribute_delete',
            ],
            [
                'id'    => 51,
                'title' => 'product_attribute_access',
            ],
            [
                'id'    => 52,
                'title' => 'specific_price_create',
            ],
            [
                'id'    => 53,
                'title' => 'specific_price_edit',
            ],
            [
                'id'    => 54,
                'title' => 'specific_price_show',
            ],
            [
                'id'    => 55,
                'title' => 'specific_price_delete',
            ],
            [
                'id'    => 56,
                'title' => 'specific_price_access',
            ],
            [
                'id'    => 57,
                'title' => 'attribute_value_create',
            ],
            [
                'id'    => 58,
                'title' => 'attribute_value_edit',
            ],
            [
                'id'    => 59,
                'title' => 'attribute_value_show',
            ],
            [
                'id'    => 60,
                'title' => 'attribute_value_delete',
            ],
            [
                'id'    => 61,
                'title' => 'attribute_value_access',
            ],
            [
                'id'    => 62,
                'title' => 'attribute_product_create',
            ],
            [
                'id'    => 63,
                'title' => 'attribute_product_edit',
            ],
            [
                'id'    => 64,
                'title' => 'attribute_product_show',
            ],
            [
                'id'    => 65,
                'title' => 'attribute_product_delete',
            ],
            [
                'id'    => 66,
                'title' => 'attribute_product_access',
            ],
            [
                'id'    => 67,
                'title' => 'product_attribute_combination_create',
            ],
            [
                'id'    => 68,
                'title' => 'product_attribute_combination_edit',
            ],
            [
                'id'    => 69,
                'title' => 'product_attribute_combination_show',
            ],
            [
                'id'    => 70,
                'title' => 'product_attribute_combination_delete',
            ],
            [
                'id'    => 71,
                'title' => 'product_attribute_combination_access',
            ],
            [
                'id'    => 72,
                'title' => 'tag_create',
            ],
            [
                'id'    => 73,
                'title' => 'tag_edit',
            ],
            [
                'id'    => 74,
                'title' => 'tag_show',
            ],
            [
                'id'    => 75,
                'title' => 'tag_delete',
            ],
            [
                'id'    => 76,
                'title' => 'tag_access',
            ],
            [
                'id'    => 77,
                'title' => 'product_tag_create',
            ],
            [
                'id'    => 78,
                'title' => 'product_tag_edit',
            ],
            [
                'id'    => 79,
                'title' => 'product_tag_show',
            ],
            [
                'id'    => 80,
                'title' => 'product_tag_delete',
            ],
            [
                'id'    => 81,
                'title' => 'product_tag_access',
            ],
            [
                'id'    => 82,
                'title' => 'order_create',
            ],
            [
                'id'    => 83,
                'title' => 'order_edit',
            ],
            [
                'id'    => 84,
                'title' => 'order_show',
            ],
            [
                'id'    => 85,
                'title' => 'order_delete',
            ],
            [
                'id'    => 86,
                'title' => 'order_access',
            ],
            [
                'id'    => 87,
                'title' => 'order_product_create',
            ],
            [
                'id'    => 88,
                'title' => 'order_product_edit',
            ],
            [
                'id'    => 89,
                'title' => 'order_product_show',
            ],
            [
                'id'    => 90,
                'title' => 'order_product_delete',
            ],
            [
                'id'    => 91,
                'title' => 'order_product_access',
            ],
            [
                'id'    => 92,
                'title' => 'banner_create',
            ],
            [
                'id'    => 93,
                'title' => 'banner_edit',
            ],
            [
                'id'    => 94,
                'title' => 'banner_show',
            ],
            [
                'id'    => 95,
                'title' => 'banner_delete',
            ],
            [
                'id'    => 96,
                'title' => 'banner_access',
            ],
            [
                'id'    => 97,
                'title' => 'price_unit_create',
            ],
            [
                'id'    => 98,
                'title' => 'price_unit_edit',
            ],
            [
                'id'    => 99,
                'title' => 'price_unit_show',
            ],
            [
                'id'    => 100,
                'title' => 'price_unit_delete',
            ],
            [
                'id'    => 101,
                'title' => 'price_unit_access',
            ],
            [
                'id'    => 102,
                'title' => 'coupon_create',
            ],
            [
                'id'    => 103,
                'title' => 'coupon_edit',
            ],
            [
                'id'    => 104,
                'title' => 'coupon_show',
            ],
            [
                'id'    => 105,
                'title' => 'coupon_delete',
            ],
            [
                'id'    => 106,
                'title' => 'coupon_access',
            ],
            [
                'id'    => 107,
                'title' => 'shipping_access',
            ],
            [
                'id'    => 108,
                'title' => 'zone_create',
            ],
            [
                'id'    => 109,
                'title' => 'zone_edit',
            ],
            [
                'id'    => 110,
                'title' => 'zone_show',
            ],
            [
                'id'    => 111,
                'title' => 'zone_delete',
            ],
            [
                'id'    => 112,
                'title' => 'zone_access',
            ],
            [
                'id'    => 113,
                'title' => 'country_create',
            ],
            [
                'id'    => 114,
                'title' => 'country_edit',
            ],
            [
                'id'    => 115,
                'title' => 'country_show',
            ],
            [
                'id'    => 116,
                'title' => 'country_delete',
            ],
            [
                'id'    => 117,
                'title' => 'country_access',
            ],
            [
                'id'    => 118,
                'title' => 'carrier_create',
            ],
            [
                'id'    => 119,
                'title' => 'carrier_edit',
            ],
            [
                'id'    => 120,
                'title' => 'carrier_show',
            ],
            [
                'id'    => 121,
                'title' => 'carrier_delete',
            ],
            [
                'id'    => 122,
                'title' => 'carrier_access',
            ],
            [
                'id'    => 123,
                'title' => 'zipcode_create',
            ],
            [
                'id'    => 124,
                'title' => 'zipcode_edit',
            ],
            [
                'id'    => 125,
                'title' => 'zipcode_show',
            ],
            [
                'id'    => 126,
                'title' => 'zipcode_delete',
            ],
            [
                'id'    => 127,
                'title' => 'zipcode_access',
            ],
            [
                'id'    => 128,
                'title' => 'module_access',
            ],
            [
                'id'    => 129,
                'title' => 'testimonial_create',
            ],
            [
                'id'    => 130,
                'title' => 'testimonial_edit',
            ],
            [
                'id'    => 131,
                'title' => 'testimonial_show',
            ],
            [
                'id'    => 132,
                'title' => 'testimonial_delete',
            ],
            [
                'id'    => 133,
                'title' => 'testimonial_access',
            ],
            [
                'id'    => 134,
                'title' => 'creative_cut_access',
            ],
            [
                'id'    => 135,
                'title' => 'creative_cuts_category_create',
            ],
            [
                'id'    => 136,
                'title' => 'creative_cuts_category_edit',
            ],
            [
                'id'    => 137,
                'title' => 'creative_cuts_category_show',
            ],
            [
                'id'    => 138,
                'title' => 'creative_cuts_category_delete',
            ],
            [
                'id'    => 139,
                'title' => 'creative_cuts_category_access',
            ],
            [
                'id'    => 140,
                'title' => 'ordernews_create',
            ],
            [
                'id'    => 141,
                'title' => 'ordernews_edit',
            ],
            [
                'id'    => 142,
                'title' => 'ordernews_show',
            ],
            [
                'id'    => 143,
                'title' => 'ordernews_delete',
            ],
            [
                'id'    => 144,
                'title' => 'ordernews_access',
            ],
            [
                'id'    => 145,
                'title' => 'shopping_cart_create',
            ],
            [
                'id'    => 146,
                'title' => 'shopping_cart_edit',
            ],
            [
                'id'    => 147,
                'title' => 'shopping_cart_show',
            ],
            [
                'id'    => 148,
                'title' => 'shopping_cart_delete',
            ],
            [
                'id'    => 149,
                'title' => 'shopping_cart_access',
            ],
            [
                'id'    => 150,
                'title' => 'enquire_access',
            ],
            [
                'id'    => 151,
                'title' => 'contact_us_create',
            ],
            [
                'id'    => 152,
                'title' => 'contact_us_edit',
            ],
            [
                'id'    => 153,
                'title' => 'contact_us_show',
            ],
            [
                'id'    => 154,
                'title' => 'contact_us_delete',
            ],
            [
                'id'    => 155,
                'title' => 'contact_us_access',
            ],
            [
                'id'    => 156,
                'title' => 'product_enquire_create',
            ],
            [
                'id'    => 157,
                'title' => 'product_enquire_edit',
            ],
            [
                'id'    => 158,
                'title' => 'product_enquire_show',
            ],
            [
                'id'    => 159,
                'title' => 'product_enquire_delete',
            ],
            [
                'id'    => 160,
                'title' => 'product_enquire_access',
            ],
            [
                'id'    => 161,
                'title' => 'email_log_create',
            ],
            [
                'id'    => 162,
                'title' => 'email_log_edit',
            ],
            [
                'id'    => 163,
                'title' => 'email_log_show',
            ],
            [
                'id'    => 164,
                'title' => 'email_log_delete',
            ],
            [
                'id'    => 165,
                'title' => 'email_log_access',
            ],
            [
                'id'    => 166,
                'title' => 'menu_create',
            ],
            [
                'id'    => 167,
                'title' => 'menu_edit',
            ],
            [
                'id'    => 168,
                'title' => 'menu_show',
            ],
            [
                'id'    => 169,
                'title' => 'menu_delete',
            ],
            [
                'id'    => 170,
                'title' => 'menu_access',
            ],
            [
                'id'    => 171,
                'title' => 'news_letter_create',
            ],
            [
                'id'    => 172,
                'title' => 'news_letter_edit',
            ],
            [
                'id'    => 173,
                'title' => 'news_letter_show',
            ],
            [
                'id'    => 174,
                'title' => 'news_letter_delete',
            ],
            [
                'id'    => 175,
                'title' => 'news_letter_access',
            ],
            [
                'id'    => 176,
                'title' => 'news_letter_menu_access',
            ],
            [
                'id'    => 177,
                'title' => 'email_news_letter_create',
            ],
            [
                'id'    => 178,
                'title' => 'email_news_letter_edit',
            ],
            [
                'id'    => 179,
                'title' => 'email_news_letter_show',
            ],
            [
                'id'    => 180,
                'title' => 'email_news_letter_delete',
            ],
            [
                'id'    => 181,
                'title' => 'email_news_letter_access',
            ],
            [
                'id'    => 182,
                'title' => 'custom_order_create',
            ],
            [
                'id'    => 183,
                'title' => 'custom_order_edit',
            ],
            [
                'id'    => 184,
                'title' => 'custom_order_show',
            ],
            [
                'id'    => 185,
                'title' => 'custom_order_delete',
            ],
            [
                'id'    => 186,
                'title' => 'custom_order_access',
            ],
            [
                'id'    => 187,
                'title' => 'review_create',
            ],
            [
                'id'    => 188,
                'title' => 'review_edit',
            ],
            [
                'id'    => 189,
                'title' => 'review_show',
            ],
            [
                'id'    => 190,
                'title' => 'review_delete',
            ],
            [
                'id'    => 191,
                'title' => 'review_access',
            ],
            [
                'id'    => 192,
                'title' => 'email_template_create',
            ],
            [
                'id'    => 193,
                'title' => 'email_template_edit',
            ],
            [
                'id'    => 194,
                'title' => 'email_template_show',
            ],
            [
                'id'    => 195,
                'title' => 'email_template_delete',
            ],
            [
                'id'    => 196,
                'title' => 'email_template_access',
            ],
            [
                'id'    => 197,
                'title' => 'order_status_create',
            ],
            [
                'id'    => 198,
                'title' => 'order_status_edit',
            ],
            [
                'id'    => 199,
                'title' => 'order_status_show',
            ],
            [
                'id'    => 200,
                'title' => 'order_status_delete',
            ],
            [
                'id'    => 201,
                'title' => 'order_status_access',
            ],
            [
                'id'    => 202,
                'title' => 'creative_cuts_enquire_create',
            ],
            [
                'id'    => 203,
                'title' => 'creative_cuts_enquire_edit',
            ],
            [
                'id'    => 204,
                'title' => 'creative_cuts_enquire_show',
            ],
            [
                'id'    => 205,
                'title' => 'creative_cuts_enquire_delete',
            ],
            [
                'id'    => 206,
                'title' => 'creative_cuts_enquire_access',
            ],
            [
                'id'    => 207,
                'title' => 'faq_create',
            ],
            [
                'id'    => 208,
                'title' => 'faq_edit',
            ],
            [
                'id'    => 209,
                'title' => 'faq_show',
            ],
            [
                'id'    => 210,
                'title' => 'faq_delete',
            ],
            [
                'id'    => 211,
                'title' => 'faq_access',
            ],
            [
                'id'    => 212,
                'title' => 'special_offer_create',
            ],
            [
                'id'    => 213,
                'title' => 'special_offer_edit',
            ],
            [
                'id'    => 214,
                'title' => 'special_offer_show',
            ],
            [
                'id'    => 215,
                'title' => 'special_offer_delete',
            ],
            [
                'id'    => 216,
                'title' => 'special_offer_access',
            ],
            [
                'id'    => 217,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 218,
                'title' => 'advertisement_banner_create',
            ],
            [
                'id'    => 219,
                'title' => 'advertisement_banner_edit',
            ],
            [
                'id'    => 220,
                'title' => 'advertisement_banner_show',
            ],
            [
                'id'    => 221,
                'title' => 'advertisement_banner_delete',
            ],
            [
                'id'    => 222,
                'title' => 'advertisement_banner_access',
            ],
            [
                'id'    => 223,
                'title' => 'print_medium_create',
            ],
            [
                'id'    => 224,
                'title' => 'print_medium_edit',
            ],
            [
                'id'    => 225,
                'title' => 'print_medium_show',
            ],
            [
                'id'    => 226,
                'title' => 'print_medium_delete',
            ],
            [
                'id'    => 227,
                'title' => 'print_medium_access',
            ],
            [
                'id'    => 228,
                'title' => 'static_page_create',
            ],
            [
                'id'    => 229,
                'title' => 'static_page_edit',
            ],
            [
                'id'    => 230,
                'title' => 'static_page_show',
            ],
            [
                'id'    => 231,
                'title' => 'static_page_delete',
            ],
            [
                'id'    => 232,
                'title' => 'static_page_access',
            ],
            [
                'id'    => 233,
                'title' => 'our_store_create',
            ],
            [
                'id'    => 234,
                'title' => 'our_store_edit',
            ],
            [
                'id'    => 235,
                'title' => 'our_store_show',
            ],
            [
                'id'    => 236,
                'title' => 'our_store_delete',
            ],
            [
                'id'    => 237,
                'title' => 'our_store_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
