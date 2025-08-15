<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Categories
    Route::post('categories/media', 'CategoriesApiController@storeMedia')->name('categories.storeMedia');
    Route::apiResource('categories', 'CategoriesApiController');

    // Feature
    Route::apiResource('features', 'FeatureApiController');

    // Attribute
    Route::apiResource('attributes', 'AttributeApiController');

    // Shop
    Route::post('shops/media', 'ShopApiController@storeMedia')->name('shops.storeMedia');
    Route::apiResource('shops', 'ShopApiController');

    // Banner
    Route::apiResource('banners', 'BannerApiController');

    // Coupon
    Route::post('coupons/media', 'CouponApiController@storeMedia')->name('coupons.storeMedia');
    Route::apiResource('coupons', 'CouponApiController');

      // Zone
      Route::apiResource('zones', 'ZoneApiController');

      // Countries
      Route::apiResource('countries', 'CountriesApiController');

      // Carrier
      Route::apiResource('carriers', 'CarrierApiController');

      // Zipcode
      Route::apiResource('zipcodes', 'ZipcodeApiController');

    // Testimonial
    Route::apiResource('testimonials', 'TestimonialApiController');

     // News Letter
     Route::apiResource('news-letters', 'NewsLetterApiController');

    // Creative Cuts Category
    Route::post('creative-cuts-categories/media', 'CreativeCutsCategoryApiController@storeMedia')->name('creative-cuts-categories.storeMedia');
    Route::apiResource('creative-cuts-categories', 'CreativeCutsCategoryApiController');

     // Order Status
     Route::apiResource('order-statuses', 'OrderStatusApiController');
      // Creative Cuts Enquire
    Route::apiResource('creative-cuts-enquires', 'CreativeCutsEnquireApiController');

     // Faqs
     Route::apiResource('faqs', 'FaqsApiController');

     // Special Offers
    Route::apiResource('special-offers', 'SpecialOffersApiController');

    // Advertisement Banner
    Route::post('advertisement-banners/media', 'AdvertisementBannerApiController@storeMedia')->name('advertisement-banners.storeMedia');
    Route::apiResource('advertisement-banners', 'AdvertisementBannerApiController');

     // Print Media
     Route::post('print-media/media', 'PrintMediaApiController@storeMedia')->name('print-media.storeMedia');
     Route::apiResource('print-media', 'PrintMediaApiController');

     // Static Page
     Route::post('static-pages/media', 'StaticPageApiController@storeMedia')->name('static-pages.storeMedia');
     Route::apiResource('static-pages', 'StaticPageApiController');

     // Our Store
     Route::post('our-stores/media', 'OurStoreApiController@storeMedia')->name('our-stores.storeMedia');
     Route::apiResource('our-stores', 'OurStoreApiController');
});
