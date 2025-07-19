<?php

use App\Enums\Permission;

use App\Http\Controllers\AdvertisementBannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaxController;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StateController;
// use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\ZipcodeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\DeliveryTimeController;
use App\Http\Controllers\FeatureValueController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AbusiveReportController;
use App\Http\Controllers\PaymentIntentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\HelpDeskController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\OurStoreController;
use App\Http\Controllers\PrintMediaController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\FaqController;

/**
 * ******************************************
 * Available Public Routes
 * ******************************************
 */
Route::post('/register', [UserController::class, 'register']);
Route::post('/token', [UserController::class, 'token']);
Route::post('/user/logout', [UserController::class, 'logout']);
Route::post('/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/verify-forget-password-token', [UserController::class, 'verifyForgetPasswordToken']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::post('/contact-us', [UserController::class, 'contactAdmin']);
Route::post('/social-login-token', [UserController::class, 'socialLogin']);
Route::post('/send-otp-code', [UserController::class, 'sendOtpToEmail']);
Route::post('/send-otp-register', [UserController::class, 'sendOtpForRegister']);
Route::post('/verify-otp-register', [UserController::class, 'verifyOtpForRegister']);
Route::post('/verify-otp-code', [UserController::class, 'verifyEmailOtp']);
Route::post('/otp-login', [UserController::class, 'otpLogin']);
Route::get('product-list', [ProductController::class, 'productList']);
Route::get('getNewArrivals', [ProductController::class, 'getNewArrivals']);
Route::post('check-postcode', [ProductController::class, 'checkPostcode']);
Route::get('getNewArrivalProducts', [ProductController::class, 'getNewArrivalProducts']);
Route::get('popular-products', [ProductController::class, 'popularProducts']);
Route::get('best-seller-products', [ProductController::class, 'bestSellerProducts']);
Route::get('featured-products', [ProductController::class, 'featuredProducts']);
Route::get('hotdeals-products', [ProductController::class, 'hotdealsProducts']);
Route::get('catalogue-products', [ProductController::class, 'catalogueProducts']);
Route::get('top-categories', [CategoryController::class, 'topCategories']);
Route::get('shopbymaterial', [CategoryController::class, 'shopbymaterial']);
Route::get('style-showcase/{parent_id}', [CategoryController::class, 'styleShowCase']);
Route::get('category-with-children', [CategoryController::class, 'getParentCateogryWithChild']);
Route::get('system-settings', [SettingsController::class, 'getSystemConfigurations']);
//Route::get('our-stores', [OurStoreController::class, 'index']);
// Route::post('our-stores/media', 'OurStoreController@storeMedia')->name('our-stores.storeMedia');
// Route::apiResource('our-stores', 'OurStoreController');
//Route::get('our-stores/{i}', [OurStoreController::class, 'index']);
Route::get('get-media-list', [PrintMediaController::class, 'getMedia']);
// Route::get('get-media', [PrintMediaController::class, 'index']);
// Route::get('get-printed-media', [PrintMediaController::class, 'getPrintedMedia']);
Route::get('show-static-page', [StaticPageController::class, 'showStaticPages']);
Route::get('get-states', [StateController::class, 'getStates']);
Route::post('send-helpdesk-mail', [HelpDeskController::class, 'sendmail']);



// Route::post('import-sheet', [ProductController::class, 'importSheet']);
Route::get('/test-header', function (Request $request) {
    return $request->header('Authorization');
});
Route::middleware(['api-logs','cors'])->group(function () {

    Route::get('top-authors', [AuthorController::class, 'topAuthor']);
    Route::get('top-manufacturers', [ManufacturerController::class, 'topManufacturer']);
    Route::get('check-availability', [ProductController::class, 'checkAvailability']);
    Route::get("products/calculate-rental-price", [ProductController::class, 'calculateRentalPrice']);
    Route::post('import-products', [ProductController::class, 'importProducts']);
    Route::post('import-variation-options', [ProductController::class, 'importVariationOptions']);
    Route::get('export-products/{shop_id}', [ProductController::class, 'exportProducts']);
    Route::get('export-variation-options/{shop_id}', [ProductController::class, 'exportVariableOptions']);
    Route::post('import-attributes', [AttributeController::class, 'importAttributes']);
    Route::get('export-attributes/{shop_id}', [AttributeController::class, 'exportAttributes']);
    Route::get('download_url/token/{token}', [DownloadController::class, 'downloadFile'])->name('download_url.token');
    Route::get('export-order/token/{token}', [OrderController::class, 'exportOrder'])->name('export_order.token');
    Route::post('subscribe-to-newsletter', [UserController::class, 'subscribeToNewsletter'])->name('subscribeToNewsletter');
    Route::get('download-invoice/token/{token}', [OrderController::class, 'downloadInvoice'])->name('download_invoice.token');
    Route::post('webhooks/razorpay', [WebHookController::class, 'razorpay']);
    Route::post('webhooks/stripe', [WebHookController::class, 'stripe']);
    Route::post('webhooks/paypal', [WebHookController::class, 'paypal']);
    Route::apiResource('products', ProductController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::post('creativecuts-enquiry', [EnquiryController::class, 'storeCretivecutsEnquiry']);
    Route::apiResource('enquiry', EnquiryController::class, [
        'only' => ['store'],
    ]);
    Route::get('product-details', [ProductController::class, 'productDetails']);


    Route::post('getSearchList', [ProductController::class, 'searchProduct']);
    Route::get('getManualSearchList', [ProductController::class, 'getManualSearchList']);
    Route::get('getSimilarProducts/{id}', [ProductController::class, 'getCategoryProduct']);
    Route::get('getOtherOrderProducts/{id}', [ProductController::class, 'otherOrderItems']);
    Route::get('get-faq-type', [FaqController::class, 'getFaqType']);
    Route::get('get-general-faq', [FaqController::class, 'getGeneralFaq']);

    Route::post('validate-slug', [ProductController::class, 'validateSlug']);


    Route::get('get-menus', [MenuController::class, 'getMenus']);
    Route::apiResource('menus', MenuController::class, [
        'only' => ['index', 'show'],
    ]);

    Route::apiResource('our-stores', OurStoreController::class, [
        'only' => ['index', 'show'],
    ]);

    Route::apiResource('types', TypeController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('attachments', AttachmentController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('categories', CategoryController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('delivery-times', DeliveryTimeController::class, [
        'only' => ['index', 'show']
    ]);
    Route::apiResource('languages', LanguageController::class, [
        'only' => ['index', 'show']
    ]);
    Route::apiResource('tags', TagController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('resources', ResourceController::class, [
        'only' => ['index', 'show']
    ]);

    Route::post('coupons/verify', [CouponController::class, 'verify']);
    // Route::apiResource('order-status', OrderStatusController::class, [
    //     'only' => ['index', 'show'],
    // ]);
    Route::apiResource('attributes', AttributeController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('shops', ShopController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('settings', SettingsController::class, [
        'only' => ['index'],
    ]);

    Route::apiResource('questions', QuestionController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('feedbacks', FeedbackController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('authors', AuthorController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('manufacturers', ManufacturerController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::post('orders/checkout/verify', [CheckoutController::class, 'verify']);

    Route::apiResource('features', FeatureController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('feature-values', FeatureValueController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::post('orders/payment', [OrderController::class, 'submitPayment']);
    Route::post('orders/cancel', [OrderController::class, 'cancelPayment']);
    Route::post('orders/{order_id}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('carts/validateCartProduct', [CartController::class, 'validateCartProduct']);


    Route::apiResource('product-attributes', ProductAttributeController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('attribute-values', AttributeValueController::class, [
        'only' => ['index', 'show'],
    ]);
    Route::apiResource('countries', CountryController::class, [
        'only' => ['index','show'],
    ]);
    Route::apiResource('zones', ZoneController::class, [
        'only' => ['index','show'],
    ]);
    Route::apiResource('states', StateController::class, [
        'only' => ['index','show'],
    ]);

    Route::apiResource('zipcodes', ZipcodeController::class, [
        'only' => ['index','show'],
    ]);

    Route::apiResource('order_status', OrderStatusController::class, [
        'only' => ['index'],
    ]);
    Route::apiResource('carriers', CarrierController::class, [
        'only' => ['index','show'],
    ]);
    Route::apiResource('banners', BannerController::class, [
        'only' => ['index','show']
    ]);
    Route::apiResource('testimonials', TestimonialController::class, [
        'only' => ['index','show','store']
    ]);
    Route::apiResource('newsletters', NewsletterController::class, [
        'only' => ['store'],
    ]);
    Route::apiResource('attachments', AttachmentController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::apiResource('carts', CartController::class, [
        'only' => ['store'],
    ]);

    Route::get('/advertisement-banners', [AdvertisementBannerController::class,'index']);
    Route::get('/getAttributeValueByAttributeCategory', [AttributeValueController::class,'getAttributeValueByAttributeCategory']);
    Route::post('/delete-user-request', [UserController::class, 'deleteUserRequest']);
    /**
     * ******************************************
     * Authorized Route for Customers only
     * ******************************************
     */

    // Route::group(['middleware' => ['can:' . Permission::CUSTOMER, 'auth:sanctum','token.expiration']], function () {
        Route::group(['middleware' => ['can:' . Permission::CUSTOMER, 'auth:sanctum']], function () {

        Route::post('/2fa-verify', [UserController::class, 'verify2fa'])->withoutMiddleware('api-logs');
        Route::post('/resend-2fa', [UserController::class, 'resendTwoFactorCode'])->withoutMiddleware('api-logs');

        Route::apiResource('notifications', NotificationController::class, [
            'only' => ['index', 'store'],
        ]);
        Route::apiResource('orders', OrderController::class, [
            'only' => ['index','show', 'store'],
        ]);
        Route::get('/payment-intent', [PaymentIntentController::class, 'getPaymentIntent']);

        Route::apiResource('questions', QuestionController::class, [
            'only' => ['store'],
        ]);
        Route::apiResource('feedbacks', FeedbackController::class, [
            'only' => ['store'],
        ]);
        Route::apiResource('abusive_reports', AbusiveReportController::class, [
            'only' => ['store'],
        ]);
        Route::get('my-questions', [QuestionController::class, 'myQuestions']);
        Route::get('my-reports', [AbusiveReportController::class, 'myReports']);
        Route::post('wishlists/toggle', [WishlistController::class, 'toggle']);
        Route::apiResource('wishlists', WishlistController::class, [
            'only' => ['index', 'store', 'destroy'],
        ]);
        Route::get('wishlists/in_wishlist/{product_id}', [WishlistController::class, 'in_wishlist']);
        Route::get('my-wishlists', [ProductController::class, 'myWishlists']);
        Route::get('orders/tracking-number/{tracking_number}', 'App\Http\Controllers\OrderController@findByTrackingNumber');

        Route::get('me', [UserController::class, 'me']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/update-contact', [UserController::class, 'updateContact']);
        Route::apiResource('address', AddressController::class, [
            'only' => ['index','show','store','update','destroy'],
        ]);
        Route::apiResource(
            'refunds',
            RefundController::class,
            [
                'only' => ['index', 'store', 'show'],
            ]
        );
        Route::get('downloads', [DownloadController::class, 'fetchDownloadableFiles']);
        Route::post('downloads/digital_file', [DownloadController::class, 'generateDownloadableUrl']);
        Route::get('/followed-shops-popular-products', [ShopController::class, 'followedShopsPopularProducts']);
        Route::get('/followed-shops', [ShopController::class, 'userFollowedShops']);
        Route::get('/follow-shop', [ShopController::class, 'userFollowedShop']);
        Route::post('/follow-shop', [ShopController::class, 'handleFollowShop']);
        Route::apiResource('cards', PaymentMethodController::class, [
            'only' => ['index', 'store', 'update', 'destroy'],
        ]);
        Route::post('/set-default-card', [PaymentMethodController::class, 'setDefaultCard']);
        Route::post('/save-payment-method', [PaymentMethodController::class, 'savePaymentMethod']);
        Route::apiResource('carts', CartController::class, [
            'only' => ['index','update','show'],
        ]);
        Route::get('/last-viewed-products', [ProductController::class, 'lastViewedProducts']);
        Route::post('/add-last-viewed-products', [ProductController::class, 'addLastViewedProducts']);
        // Custom Order
        Route::post('custom-orders/media', [CustomOrderController::class,'storeMedia'])->name('custom-orders.storeMedia');
        Route::apiResource('custom-orders', CustomOrderController::class, [
            'only' => ['index', 'store','update','show'],
        ]);
        Route::apiResource('coupons', CouponController::class, [
            'only' => ['index', 'show'],
        ]);
        Route::post('download-invoice-url', 'App\Http\Controllers\OrderController@downloadInvoiceUrl');
        Route::apiResource('enquiry', EnquiryController::class, [
            'only' => ['index'],
        ]);

        Route::post('reviews/media', [ReviewController::class,'storeMedia'])->name('reviews.storeMedia');
        Route::apiResource('reviews', ReviewController::class, [
            'only' => ['index', 'show','store', 'update'],
        ]);
    });

    /**
     * ******************************************
     * Authorized Route for Staff & Store Owner
     * ******************************************
     */

    Route::group(
        ['middleware' => ['permission:' . Permission::STAFF . '|' . Permission::STORE_OWNER, 'auth:sanctum']],
        function () {
            Route::get('analytics', [AnalyticsController::class, 'analytics']);
            Route::apiResource('products', ProductController::class, [
                'only' => ['store', 'update', 'destroy'],
            ]);
            Route::apiResource('resources', ResourceController::class, [
                'only' => ['store']
            ]);
            Route::apiResource('attributes', AttributeController::class, [
                'only' => ['store', 'update', 'destroy'],
            ]);
            Route::apiResource('attribute-values', AttributeValueController::class, [
                'only' => ['store', 'update', 'destroy'],
            ]);
            Route::get('getAttributesValues/{id}', [AttributeValueController::class, 'getAttributeValues']);
            Route::apiResource('product-attributes', ProductAttributeController::class, [
                'only' => ['store', 'update', 'destroy'],
            ]);
            Route::apiResource('orders', OrderController::class, [
                'only' => ['update', 'destroy'],
            ]);
            // Route::get('popular-products', [AnalyticsController::class, 'popularProducts']);
            // Route::get('shops/refunds', 'App\Http\Controllers\ShopController@refunds');
            Route::apiResource('questions', QuestionController::class, [
                'only' => ['update'],
            ]);
            Route::apiResource('authors', AuthorController::class, [
                'only' => ['store'],
            ]);
            Route::apiResource('manufacturers', ManufacturerController::class, [
                'only' => ['store'],
            ]);

            Route::post('updateReviewStatus/{id}', [ReviewController::class, 'reviewStatusUpdate']);

            Route::get('export-order-url/{shop_id?}', 'App\Http\Controllers\OrderController@exportOrderUrl');

        }
    );


    /**
     * *****************************************
     * Authorized Route for Store owner Only
     * *****************************************
     */

    Route::group(
        ['middleware' => ['permission:' . Permission::STORE_OWNER, 'auth:sanctum']],
        function () {
            Route::apiResource('shops', ShopController::class, [
                'only' => ['store', 'update', 'destroy'],
            ]);
            Route::apiResource('withdraws', WithdrawController::class, [
                'only' => ['store', 'index', 'show'],
            ]);
            Route::post('staffs', [ShopController::class, 'addStaff']);
            Route::delete('staffs/{id}', [ShopController::class, 'deleteStaff']);
            Route::get('staffs', [UserController::class, 'staffs']);
            Route::get('my-shops', [ShopController::class, 'myShops']);
        }
    );

    /**
     * *****************************************
     * Authorized Route for Super Admin only
     * *****************************************
     */

    Route::group(['middleware' => ['permission:' . Permission::SUPER_ADMIN, 'auth:sanctum']], function () {
        Route::apiResource('types', TypeController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('withdraws', WithdrawController::class, [
            'only' => ['update', 'destroy'],
        ]);
        Route::apiResource('categories', CategoryController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('delivery-times', DeliveryTimeController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('languages', LanguageController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('tags', TagController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('resources', ResourceController::class, [
            'only' => ['update', 'destroy']
        ]);
        Route::apiResource('coupons', CouponController::class, [
            'only' => ['store', 'update', 'destroy','create'],
        ]);
        Route::get('coupon/create', [CouponController::class, 'createForm']);
        // Route::apiResource('order-status', OrderStatusController::class, [
        //     'only' => ['store', 'update', 'destroy'],
        // ]);
        Route::apiResource('reviews', ReviewController::class, [
            'only' => ['destroy']
        ]);
        Route::apiResource('questions', QuestionController::class, [
            'only' => ['destroy'],
        ]);
        Route::apiResource('feedbacks', QuestionController::class, [
            'only' => ['update', 'destroy'],
        ]);
        Route::apiResource('abusive_reports', AbusiveReportController::class, [
            'only' => ['index', 'show', 'update', 'destroy'],
        ]);
        Route::post('abusive_reports/accept', [AbusiveReportController::class, 'accept']);
        Route::post('abusive_reports/reject', [AbusiveReportController::class, 'reject']);
        Route::apiResource('settings', SettingsController::class, [
            'only' => ['store'],
        ]);
        Route::apiResource('users', UserController::class);
        Route::apiResource('authors', AuthorController::class, [
            'only' => ['update', 'destroy'],
        ]);
        Route::apiResource('manufacturers', ManufacturerController::class, [
            'only' => ['update', 'destroy'],
        ]);
        Route::post('users/block-user', [UserController::class, 'banUser']);
        Route::post('users/unblock-user', [UserController::class, 'activeUser']);
        Route::apiResource('taxes', TaxController::class);
        Route::apiResource('shippings', ShippingController::class);
        Route::post('approve-shop', [ShopController::class, 'approveShop']);
        Route::post('disapprove-shop', [ShopController::class, 'disApproveShop']);
        Route::post('approve-withdraw', [WithdrawController::class, 'approveWithdraw']);
        Route::post('add-points', [UserController::class, 'addPoints']);
        Route::post('users/make-admin', [UserController::class, 'makeOrRevokeAdmin']);
        Route::apiResource(
            'refunds',
            RefundController::class,
            [
                'only' => ['destroy', 'update'],
            ]
        );
        Route::apiResource('features', FeatureController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('feature-values', FeatureValueController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('zones', ZoneController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('countries', CountryController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('zipcodes', ZipcodeController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('order_status', OrderStatusController::class, [
            'only' => ['update']
        ]);
        Route::apiResource('carriers', CarrierController::class, [
            'only' => ['store','update','destroy']
        ]);
    });

    Route::group(['middleware' => [ 'auth:sanctum']], function () {
        Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);
        Route::post('coupons/apply', [CouponController::class, 'applyCoupon']);
        Route::get('couponslist', [CouponController::class, 'index']);
    });

});
