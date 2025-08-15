<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\FeatureValueController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\MailTestController;
use App\Imports\ImportProduct;
use Maatwebsite\Excel\Facades\Excel;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/send-test-mail', [MailTestController::class, 'sendTestMail']);
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::post('categories/media', 'CategoriesController@storeMedia')->name('categories.storeMedia');
    Route::post('categories/ckmedia', 'CategoriesController@storeCKEditorImages')->name('categories.storeCKEditorImages');
    Route::post('categories/update-positions', 'CategoriesController@updatePositions')->name('categories.updatePositions');
    Route::post('/categories/updateStatus', 'CategoriesController@updateStatus')->name('categories.updateStatus');
    Route::resource('categories', 'CategoriesController');

    // Feature
    Route::delete('features/destroy', 'FeatureController@massDestroy')->name('features.massDestroy');
    Route::post('features/update-positions', 'FeatureController@updatePositions')->name('features.updatePositions');
    Route::resource('features', 'FeatureController');
    Route::delete('feature-values/destroy', 'FeatureValueController@massDestroy')->name('feature-values.massDestroy');
    Route::resource('feature-values', 'FeatureValueController');




    // Attribute
    Route::delete('attributes/destroy', 'AttributeController@massDestroy')->name('attributes.massDestroy');
    Route::post('attributes/update-positions', 'AttributeController@updatePositions')->name('attributes.updatePositions');
    Route::resource('attributes', 'AttributeController');

    // Shop
    Route::delete('shops/destroy', 'ShopController@massDestroy')->name('shops.massDestroy');
    Route::post('shops/media', 'ShopController@storeMedia')->name('shops.storeMedia');
    Route::post('shops/ckmedia', 'ShopController@storeCKEditorImages')->name('shops.storeCKEditorImages');
    Route::resource('shops', 'ShopController');

    // Product
    //Route::get('products/import', 'ProductController@importProductfile')->name('products.importProductfile');
    Route::get('products/export', 'ProductController@exportProductfile')->name('product.export-sample-file');
    Route::post('products/import', 'ProductController@importProductfile')->name('product.import-product');
    Route::get('products/import-status', 'ProductController@importProductfileStatus')->name('product.import-product-status');
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/massUpdate', 'ProductController@massUpdate')->name('products.massUpdate');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::post('/products/updateStatus', 'ProductController@updateStatus')->name('products.updateStatus');
    Route::get('/products/check-slug/{slug}', [ProductController::class, 'validateSlug'])->name('products.validateSlug');
    Route::resource('products', 'ProductController');

    // Product Feature
    Route::delete('product-features/destroy', 'ProductFeatureController@massDestroy')->name('product-features.massDestroy');
    Route::resource('product-features', 'ProductFeatureController');

    // Specific Price
    Route::delete('specific-prices/destroy', 'SpecificPriceController@massDestroy')->name('specific-prices.massDestroy');
    Route::resource('specific-prices', 'SpecificPriceController');

    // Product Attribute
    Route::delete('product-attributes/destroy', 'ProductAttributeController@massDestroy')->name('product-attributes.massDestroy');
    Route::post('product-attributes/media', 'ProductAttributeController@storeMedia')->name('product-attributes.storeMedia');
    Route::post('product-attributes/ckmedia', 'ProductAttributeController@storeCKEditorImages')->name('product-attributes.storeCKEditorImages');
    Route::post('product-attributes/update-image', 'ProductAttributeController@updateImage')->name('product-attributes.update-image');
    Route::post('product-attributes/update-positions', 'ProductAttributeController@updatePositions')->name('product-attributes.updatePositions');
    Route::post('product-attributes/massUpdate', 'ProductAttributeController@massUpdate')->name('product-attributes.massUpdate');
    Route::post('product-attributes/bulkUpdate', 'ProductAttributeController@bulkUpdate')->name('product-attributes.bulkUpdate');

    Route::resource('product-attributes', 'ProductAttributeController');

    // Attribute Value
    Route::delete('attribute-values/destroy', 'AttributeValueController@massDestroy')->name('attribute-values.massDestroy');
    Route::post('attribute-values/media', 'ProductAttributeController@storeMedia')->name('attribute-values.storeMedia');
    Route::get('get-attribute-values', 'AttributeValueController@getAttributeValues')->name('attribute-values.get');
    Route::post('attribute-values/update-positions', 'AttributeValueController@updatePositions')->name('attribute-values.updatePositions');
    Route::resource('attribute-values', 'AttributeValueController');

    // Attribute Product
    Route::delete('attribute-products/destroy', 'AttributeProductController@massDestroy')->name('attribute-products.massDestroy');
    Route::resource('attribute-products', 'AttributeProductController');

    // Product Attribute Combination
    Route::delete('product-attribute-combinations/destroy', 'ProductAttributeCombinationController@massDestroy')->name('product-attribute-combinations.massDestroy');
    Route::resource('product-attribute-combinations', 'ProductAttributeCombinationController');

    // Tag
    Route::delete('tags/destroy', 'TagController@massDestroy')->name('tags.massDestroy');
    Route::resource('tags', 'TagController');

    // Product Tag
    Route::delete('product-tags/destroy', 'ProductTagController@massDestroy')->name('product-tags.massDestroy');
    Route::resource('product-tags', 'ProductTagController');

    // Order
    Route::get('/orders/download-invoice/{order_id}', 'OrderController@downloadInvoice')->name('orders.downloadInvoice');
    Route::delete('orders/destroy', 'OrderController@massDestroy')->name('orders.massDestroy');
    Route::post('/orders/update-order-status', 'OrderController@updateOrderStatus')->name('orders.updateOrderStatus');
    Route::post('/orders/update-shipping-carriers', 'OrderController@updateShippingCarriers')->name('orders.updateShippingCarriers');

    Route::resource('orders', 'OrderController');

    // Order Product
    Route::delete('order-products/destroy', 'OrderProductController@massDestroy')->name('order-products.massDestroy');
    Route::resource('order-products', 'OrderProductController');

    // Banner
    Route::post('/banners/updateStatus', 'BannerController@updateStatus')->name('banners.updateStatus');
    Route::delete('banners/destroy', 'BannerController@massDestroy')->name('banners.massDestroy');
    Route::post('banners/media', 'BannerController@storeMedia')->name('banners.storeMedia');
    Route::post('banners/ckmedia', 'BannerController@storeCKEditorImages')->name('banners.storeCKEditorImages');
    Route::resource('banners', 'BannerController');

    // Price Unit
    Route::delete('price-units/destroy', 'PriceUnitController@massDestroy')->name('price-units.massDestroy');
    Route::resource('price-units', 'PriceUnitController');

    // Coupon
    Route::delete('coupons/destroy', 'CouponController@massDestroy')->name('coupons.massDestroy');
    Route::post('coupons/media', 'CouponController@storeMedia')->name('coupons.storeMedia');
    Route::post('coupons/ckmedia', 'CouponController@storeCKEditorImages')->name('coupons.storeCKEditorImages');
    Route::resource('coupons', 'CouponController');

    // Zone
    Route::delete('zones/destroy', 'ZoneController@massDestroy')->name('zones.massDestroy');
    Route::resource('zones', 'ZoneController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Carrier
    Route::delete('carriers/destroy', 'CarrierController@massDestroy')->name('carriers.massDestroy');
    Route::resource('carriers', 'CarrierController');

    // Zipcode
    Route::delete('zipcodes/destroy', 'ZipcodeController@massDestroy')->name('zipcodes.massDestroy');
    Route::resource('zipcodes', 'ZipcodeController');

    //regions
    Route::delete('regions/destroy', 'RegionController@massDestroy')->name('regions.massDestroy');
    Route::post('/get-postcodes', 'RegionController@getPostcodes')->name('get-postcodes');
    Route::post('/postcode-suggestions', 'RegionController@validatePostcode')->name('postcode-suggestions');
    Route::resource('regions', 'RegionController');

    // Testimonial
    Route::post('testimonials/media', 'TestimonialController@storeMedia')->name('testimonials.storeMedia');
    Route::delete('testimonials/destroy', 'TestimonialController@massDestroy')->name('testimonials.massDestroy');
    Route::resource('testimonials', 'TestimonialController');
    Route::post('/testimonials/updateStatus', 'TestimonialController@updateStatus')->name('testimonials.updateStatus');

    // Creative Cuts
    Route::post('/creative-cuts-categories/updateStatus', 'CreativeCutsCategoryController@updateStatus')->name('creative-cuts-categories.updateStatus');
    Route::delete('creative-cuts-categories/destroy', 'CreativeCutsCategoryController@massDestroy')->name('creative-cuts-categories.massDestroy');
    Route::post('creative-cuts-categories/media', 'CreativeCutsCategoryController@storeMedia')->name('creative-cuts-categories.storeMedia');
    Route::post('creative-cuts-categories/ckmedia', 'CreativeCutsCategoryController@storeCKEditorImages')->name('creative-cuts-categories.storeCKEditorImages');
    Route::resource('creative-cuts-categories', 'CreativeCutsCategoryController');


     // Shopping Cart
     Route::delete('shopping-carts/destroy', 'ShoppingCartController@massDestroy')->name('shopping-carts.massDestroy');
     Route::resource('shopping-carts', 'ShoppingCartController');

     // Contact Us
     Route::delete('contact-uss/destroy', 'ContactUsController@massDestroy')->name('contact-uss.massDestroy');
     Route::post('contact-uss/media', 'ContactUsController@storeMedia')->name('contact-uss.storeMedia');
     Route::post('contact-uss/ckmedia', 'ContactUsController@storeCKEditorImages')->name('contact-uss.storeCKEditorImages');
     Route::resource('contact-uss', 'ContactUsController');

     // Product Enquire
     Route::delete('product-enquires/destroy', 'ProductEnquireController@massDestroy')->name('product-enquires.massDestroy');
     Route::post('product-enquires/media', 'ProductEnquireController@storeMedia')->name('product-enquires.storeMedia');
     Route::post('product-enquires/ckmedia', 'ProductEnquireController@storeCKEditorImages')->name('product-enquires.storeCKEditorImages');
     Route::resource('product-enquires', 'ProductEnquireController');

     // Menu
    // Route::delete('menus/destroy', 'MenuController@massDestroy')->name('menus.massDestroy');
    Route::post('menu/media', 'MenuController@storeMedia')->name('menus.storeMedia');
    // Route::post('menus/update-positions', 'MenuController@reorder')->name('menus.updatePositions');
    // Route::post('/menus/updateStatus', 'MenuController@updateStatus')->name('menus.updateStatus');
    // Route::resource('menus', 'MenuController');

    Route::get('menus',[MenuController::class,'index'])->name('manage-menus');
    Route::post('create-menu',[MenuController::class,'store'])->name('create-menu');
    Route::get('add-categories-to-menu',[MenuController::class,'addCatToMenu'])->name('add-categories-to-menu');
    Route::get('add-post-to-menu',[MenuController::class,'addPostToMenu'])->name('add-post-to-menu');
    Route::get('add-custom-link',[MenuController::class,'addCustomLink'])->name('add-custom-link');
    Route::post('update-menu',[MenuController::class,'updateMenu'])->name('update.mega-menu');
    Route::post('update-menuitem/{id}',[MenuController::class,'updateMenuItem'])->name('update-menuitem');
    Route::get('delete-menuitem/{id}/{key}/{in?}/{in2?}',[MenuController::class,'deleteMenuItem'])->name('remove-menu-item');
    Route::get('delete-menu/{id}',[MenuController::class,'destroy'])->name('deleteMenu');

     // News Letter
     Route::delete('news-letters/destroy', 'NewsLetterController@massDestroy')->name('news-letters.massDestroy');
     Route::post('/news-letters/updateStatus', 'NewsLetterController@updateStatus')->name('news-letters.updateStatus');
     Route::resource('news-letters', 'NewsLetterController');


    // Customer Newsletter
     Route::delete('customer-news-letters/destroy', 'CustomerNewsLetterController@massDestroy')->name('customer-news-letters.massDestroy');
     Route::post('/customer-news-letters/updateStatus', 'CustomerNewsLetterController@updateStatus')->name('customer-news-letters.updateStatus');
     Route::resource('customer-news-letters', 'CustomerNewsLetterController');

    // Review
    Route::delete('reviews/destroy', 'ReviewController@massDestroy')->name('reviews.massDestroy');
    Route::post('reviews/media', 'ReviewController@storeMedia')->name('reviews.storeMedia');
    Route::post('reviews/ckmedia', 'ReviewController@storeCKEditorImages')->name('reviews.storeCKEditorImages');
    Route::post('/reviews/updateStatus', 'ReviewController@updateStatus')->name('reviews.updateStatus');
    Route::resource('reviews', 'ReviewController');

    // Email Template
    Route::delete('email-templates/destroy', 'EmailTemplateController@massDestroy')->name('email-templates.massDestroy');
    Route::post('/email-templates/updateStatus', 'EmailTemplateController@updateStatus')->name('email-templates.updateStatus');
    Route::resource('email-templates', 'EmailTemplateController');

    // Order Status
    Route::delete('order-statuses/destroy', 'OrderStatusController@massDestroy')->name('order-statuses.massDestroy');
    Route::post('/order-statuses/updateStatus', 'OrderStatusController@updateStatus')->name('order-statuse.updateStatus');
    Route::resource('order-statuses', 'OrderStatusController');

     // Creative Cuts Enquire
     Route::delete('creative-cuts-enquires/destroy', 'CreativeCutsEnquireController@massDestroy')->name('creative-cuts-enquires.massDestroy');
     Route::resource('creative-cuts-enquires', 'CreativeCutsEnquireController');

    // Faqs
    Route::delete('faqs/destroy', 'FaqsController@massDestroy')->name('faqs.massDestroy');
    Route::resource('faqs', 'FaqsController');


    // Special Offers
    Route::delete('special-offers/destroy', 'SpecialOffersController@massDestroy')->name('special-offers.massDestroy');
    Route::resource('special-offers', 'SpecialOffersController');

    // Advertisement Banner
    Route::delete('advertisement-banners/destroy', 'AdvertisementBannerController@massDestroy')->name('advertisement-banners.massDestroy');
    Route::post('advertisement-banners/media', 'AdvertisementBannerController@storeMedia')->name('advertisement-banners.storeMedia');
    Route::post('advertisement-banners/ckmedia', 'AdvertisementBannerController@storeCKEditorImages')->name('advertisement-banners.storeCKEditorImages');
    Route::resource('advertisement-banners', 'AdvertisementBannerController');

    Route::get('configurations',[ConfigurationController::class,'edit'])->name('edit-configuration');
    Route::patch('configurations/update',[ConfigurationController::class,'update'])->name('update-configuration');

     // Print Media
     Route::delete('print-media/destroy', 'PrintMediaController@massDestroy')->name('print-media.massDestroy');
     Route::post('print-media/media', 'PrintMediaController@storeMedia')->name('print-media.storeMedia');
     Route::post('print-media/ckmedia', 'PrintMediaController@storeCKEditorImages')->name('print-media.storeCKEditorImages');
     Route::resource('print-media', 'PrintMediaController');

     // Static Page
     Route::delete('static-pages/destroy', 'StaticPageController@massDestroy')->name('static-pages.massDestroy');
     Route::post('static-pages/media', 'StaticPageController@storeMedia')->name('static-pages.storeMedia');
     Route::post('static-pages/ckmedia', 'StaticPageController@storeCKEditorImages')->name('static-pages.storeCKEditorImages');
     Route::resource('static-pages', 'StaticPageController');

     // Our Store
     Route::delete('our-stores/destroy', 'OurStoreController@massDestroy')->name('our-stores.massDestroy');
     Route::post('our-stores/media', 'OurStoreController@storeMedia')->name('our-stores.storeMedia');
     Route::post('our-stores/ckmedia', 'OurStoreController@storeCKEditorImages')->name('our-stores.storeCKEditorImages');
     Route::resource('our-stores', 'OurStoreController');


});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
