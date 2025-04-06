<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Auth::routes();

// Homepage Route
Route::get('/', 'HomeController@index')->name('home');

// PWA Routes
Route::get('manifest.json', 'PWAController@manifest')->name('pwa-manifest');
Route::get('offline', 'PWAController@offline')->name('pwa-offline');


// Report content route
Route::get('report-content', 'HomeController@report')->name('report');
Route::post('store-report', 'HomeController@storeReport')->name('storeReport');

// User Profile
Route::get('my-profile', 'ProfileController@create')->name('startMyPage');
Route::post('profile/update', 'ProfileController@store')->name('storeMyPage');
Route::get('profile/set-fee', 'ProfileController@setFee')->name('profile.setFee');
Route::Post('profile/save-fee', 'ProfileController@saveMembershipFee')->name('saveMembershipFee');
Route::get('profile/verification', 'ProfileController@verifyProfile')->name('profile.verifyProfile');
Route::post('profile/process-verification', 'ProfileController@processVerification')->name('processVerification');
Route::get('profile/settings', 'ProfileController@accountSettings')->name('accountSettings');
Route::post('profile/save-settings', 'ProfileController@saveAccountSettings')->name('saveAccountSettings');
Route::post('profile/follow/{user}', 'ProfileController@followUser')->name('followUser');
Route::get('profile/my-subscribers', 'ProfileController@mySubscribers')->name('mySubscribers');
Route::get('profile/my-subscriptions', 'ProfileController@mySubscriptions')->name('mySubscriptions');
Route::get('feed/loadMore/{profile}/{lastId}', 'ProfileController@ajaxFeedForProfile')->name('loadPostsForProfile');
Route::get('profile/my-tips', 'TipsController@myTips')->name('myTips');
Route::get('profile/my-unlocks', 'TipsController@myUnlocks')->name('myUnlocksList');
Route::get('profile/get/profilePicture', 'ProfileController@getProfilePic')->name('profile.getPic');

// Billing
Route::get('billing/history', 'BillingController@history')->name('billing.history');
Route::get('billing/cards', 'BillingController@cards')->name('billing.cards');

// Stripe
Route::get('stripe/add-card', 'StripeController@addStripeCard')->name('addStripeCard');
Route::get('stripe/capture-card', 'StripeController@captureStripeCard')->name('captureStripeCard');
Route::post('stripe/stripeHooks', 'StripeController@stripeHooks')->name('stripeHooks');

// Payments
Route::get('subscribe/card/{user}', 'SubscriptionController@credit_card')->name('subscribeCreditCard');
Route::get('subscribe/ccbill/{user}', 'SubscriptionController@ccbill')->name('subscribeCCBill');
Route::get('subscribe/paystack/{user}', 'SubscriptionController@payStack')->name('subscribePayStack');
Route::get('subscribe/paypal/{user}', 'SubscriptionController@paypal')->name('subscribeViaPaypal');
Route::post('subscribe/paypal-notify/{creator}/{subscriber}', 'SubscriptionController@paypalProcessing')->name('paypalProcessing');
Route::get('subscribe/webpayplus/{user}', 'WebpayPlusController@create_subscription')->name('subscribeWithWBPlus');
Route::post('subscribe/process-webpayplus', 'WebpayPlusController@process_subscription')->name('wpb-process-subscription');
Route::get('subscribe/mercadopago/{user}', 'MercadoPagoController@subscribeToUser')->name('subscribeMercadoPago');
Route::get('subscribe/coinpayments/{user}', 'SubscriptionController@coinpayments')->name('subscribeCoinPayments');
Route::post('subscribe/coinpayments/{creator}/{subscriber}', 'SubscriptionController@coinPaymentsProcessing')->name('coinPaymentsProcessing');
Route::get('subscribe/coinPaymentsReturn', 'SubscriptionController@coinPaymentsReturn')->name('coinPaymentsReturn');

// Tips
Route::post('tip/send/{post}', 'TipsController@processTip')->name('sendTip');
Route::post('tip/paypal/ipn/{creator}/{subscriber}/{post}', 'TipsController@processPayPalTip')->name('paypalTipIPN');
Route::any('tip/paypal/go-to-post/{post}', 'TipsController@redirectPayPalToPost')->name('paypal-post');
Route::any('tip/ccbill', 'TipsController@process')->name('ccbill-post');
Route::any('tip/ccbill/approval', 'CCBillController@approval')->name('ccbill-approval');
Route::get('tip/mercadopago/return', 'TipsController@mercadoPagoTipProcess')->name('mercadoPagoTipIPN');
Route::any('tip/coinpayments/ipn', 'TipsController@coinPaymentsTipIPN')->name('coinPaymentsTipIPN');
Route::get('tip/coinpayments/return', 'TipsController@coinPaymentsTipReturn')->name('coinPaymentsTipReturn');

// CCBill Webhooks
Route::any('ccbill/webhooks', 'CCBillController@webhooks')->name('ccbill-hooks');

// PayStack Webhooks
Route::get('paystack/add-card', 'PayStackController@addPayStackCard')->name('addPayStackCard');
Route::post('paystack/authorization', 'PayStackController@redirectToAuthorization')->name('paystack-authorization');
Route::get('paystack/store-authorization', 'PayStackController@storeAuthorization')->name('paystack-authorization-callback');
Route::post('paystack/webhooks', 'PayStackController@webhooks')->name('paystack-hooks');
Route::get('paystack/link', 'PayStackController@link')->name('paystack-link');

// MercadoPago Webhooks
Route::post('mercadopago/store-authorization', 'MercadoPagoController@storeAuthorization')->name('mercadopago-authorization-callback');
Route::get('mercadopago/subscription/return', 'MercadoPagoController@mercadoPagoSubscriptionIPN')->name('mercadoPagoSubscriptionIPN');

// TransBank GateWay - Webpay Plus
Route::get('webpayplus/create/{post}/{creator}/{tipper}/{amount}', 'WebpayPlusController@createdTransaction')->name('wbp-process-create');
Route::geT('webpayplus/create-unlock/{message}/{lockPrice}', 'WebpayPlusController@createUnlockTx')->name('wbp-msg-unlock');
Route::post('webpayplus/returnUrl', 'WebpayPlusController@commitTransaction')->name('wpb-return-url');
Route::post('webpayplus/returnUrlTx', 'WebpayPlusController@commitUnlockTransaction')->name('wpb-return-tx');

// Withdrawals
Route::get('withdrawals', 'WithdrawalControlller@index')->name('profile.withdrawal');

// Notifications
Route::get('notifications', 'NotificationsController@index')->name('notifications.index');

// Posts
Route::get('post/{post}', 'PostsController@singlePost')->name('single-post');
Route::get('post/edit/{post}', 'PostsController@editPost')->name('editPost');
Route::post('post/save/{post}', 'PostsController@updatePost')->name('updatePost');
Route::post('save-post', 'PostsController@savePost')->name('savePost');
Route::get('pin-post/{post}', 'PostsController@pinPost')->name('pinPost');
Route::get('remove-post-pin/{post}', 'PostsController@removePin')->name('removePin');
Route::get('delete-post/{post}', 'PostsController@deletePost')->name('deletePost');
Route::get('post/loadById/{post}', 'PostsController@loadAjaxSingle')->name('loadPostById');
Route::get('post/loadMore/{lastId}', 'PostsController@ajaxFeed')->name('loadMorePosts');
Route::get('post/delete-media/{post}', 'PostsController@deleteMedia')->name('deleteMedia');
Route::get('post/download-zip/{post}', 'PostsController@downloadZip')->name('downloadZip');
Route::post('post/attach-media/{post}', 'PostsController@attachMedia')->name('posts.attachMedia');
Route::get('post/json/{post}', 'PostsController@postJSON')->name('posts.json');
Route::post('post/vueapp/api', 'PostsController@vueapi')->name('posts.vueapi');

Route::enum('post-enum');

// Likes
Route::post('like/{post}', 'LikeController@like')->name('likePost');

// Comments
Route::get('comments/{post}/{lastId?}', 'CommentsController@loadForPost')->name('loadCommentsForPost');
Route::post('comment/{post}', 'CommentsController@postComment')->name('postComment');
Route::get('comment/load/{comment}/{post}', 'CommentsController@loadCommentById')->name('loadCommentById');
Route::get('comment/delete/{comment}', 'CommentsController@deleteComment')->name('deleteComment');
Route::get('comment/form/{comment}', 'CommentsController@editComment')->name('editComment');
Route::post('comment/update/store', 'CommentsController@updateComment')->name('updateComment');

// Serve image securely
Route::get('usermedia/{path}', 'ImageController@picture')->where('path', '.*')->name('serveUserImage');

// Dashboard
Route::get('feed', 'PostsController@feed')->name('feed');
Route::get('me', function () {
    return redirect()->route('feed');
});

// Browse Creators
Route::get('browse-creators/{category?}/{category_name?}', 'BrowseCreators@browse')->name('browseCreators');

// Messages
Route::get('messages', 'MessagesController@inbox')->name('messages.inbox');
Route::get('messages/{user}', 'MessagesController@conversation')->name('messages.conversation');
Route::post('messages/get/auth-user', 'MessagesController@getAuthUser')->name('message.getAuthUser');
Route::get('messages/download-zip/{messageMedia}', 'MessagesController@downloadZip')->name('downloadMessageZip');
Route::get('unlock-message/{gateway}/{message}', 'MessagesController@unlockMessage')->name('unlockMessage');
Route::post('paypal/unlock/{message}', 'MessagesController@processPayPalUnlocking')->name('paypalUnlockIpn');
Route::get('mercadopago/unlock/return', 'MessagesController@mercadoPagoUnlockProcess')->name('mercadoPagoUnlockIPN');
Route::any('coinpayments/unlock/{message}', 'MessagesController@coinPaymentsUnlockIPN')->name('coinPaymentsUnlockIPN');
Route::get('messages/get/recipients', 'MessagesController@getRecipientsForUser')->name('messages.getRecipientsForUser');
Route::get('messages/get/serverTime', 'MessagesController@getServerTime')->name('messages.getServerTime');
Route::get('messages/get/media/{media}', 'MessagesController@getMedia')->name('messages.getMedia');
Route::post('messages/send/{recipient}', 'MessagesController@sendMessage')->name('messages.sendMessage');
Route::post('messages/attach-media/{message}', 'MessagesController@attachMedia')->name('messages.attachMedia');

// Pages Routes
Route::get('p/{page}', 'PageController')->name('page');

// Entry popup
Route::get('entry-popup', 'HomeController@entryPopupCookie')->name('entryPopupCookie');

// Product
Route::view('validate-license', 'validate-license')->name('validate-license');
Route::post('validate-license', 'Controller@activate_product');

// Contact page
Route::get('contact-page', 'ContactController@contact_page')->name('contact-page');
Route::post('contact-page-process', 'ContactController@contact_form_process')->name('contact-form-process');

// Redirect to external links
Route::get('external-redirect', 'PostsController@externalLinkRedirect')->name('external-url');

// Banned Route
Route::view('banned', 'banned')->name('banned-ip');

// Admin Routes
Route::any('admin/login', 'Admin@login')->name('adminLogin');
Route::any('admin/logout', 'Admin@logout')->name('adminlogout');

// admin panel routes
Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {

    Route::get('admin', 'Admin@dashboard');

    // Vendors Related
    Route::get('admin/users', 'Admin@users');
    Route::get('admin/loginAs/{vendorId}', 'Admin@loginAsVendor');
    Route::get('admin/add-plan/{vendorId}', 'Admin@addPlanManually');
    Route::post('admin/add-plan/{vendorId}', 'Admin@addPlanManuallyProcess');
    Route::get('admin/users/setadmin/{user}', 'Admin@setAdminRole');
    Route::get('admin/users/unsetadmin/{user}', 'Admin@unsetAdminRole');
    Route::get('admin/users/ban/{user}', 'Admin@banUser');
    Route::get('admin/users/unban/{user}', 'Admin@unbanUser');
    Route::get('admin/users/custom-fee/{user}', 'Admin@customFee');
    Route::post('admin/users/save-custom-fee/{user}', 'Admin@customFeeProcess');

    // Profile Related
    Route::get('admin/profile-verifications', 'Admin@profileVerifications')->name('admin-pvf');
    Route::get('admin/approve/{profile}', 'Admin@approveProfile');
    Route::get('admin/reject/{profile}', 'Admin@rejectProfile');

    // Payment Requests Related
    Route::get('admin/payment-requests', 'Admin@paymentRequests')->name('admin.payment-requests');
    Route::get('admin/payment-requests/markAsPaid/{withdraw}', 'Admin@markPaymentRequestAsPaid');

    // Tx Related
    Route::get('admin/tx', 'Admin@tx');

    // Subscriptions related
    Route::get('admin/subscriptions', 'Admin@subscriptions');

    // Tips Related
    Route::get('admin/tips', 'Admin@tips');

    // Unlocks (messages)
    Route::get('admin/unlocks', 'Admin@unlocks');

    // Category Related
    Route::get('admin/categories', 'Admin@categories_overview');
    Route::post('admin/add_category', 'Admin@add_category');
    Route::post('admin/update_category', 'Admin@update_category');

    // CMS 
    Route::get('admin/cms', 'Admin@pages')->name('admin-cms');
    Route::post('admin/cms', 'Admin@create_page');
    Route::get('admin/cms-edit/{page}', 'Admin@showUpdatePage');
    Route::post('admin/cms-edit/{page}', 'Admin@processUpdatePage');
    Route::get('admin/cms-delete/{page}', 'Admin@deletePage');
    Route::post('admin/cms/upload-image', 'Admin@uploadImageFromCMS');

    // Payments Setup
    Route::get('admin/payments-settings', 'Admin@paymentsSetup');
    Route::post('admin/save-payments-settings', 'Admin@paymentsSetupProcess');

    // Extra CSS/JS
    Route::get('admin/cssjs', 'Admin@extraCSSJS');
    Route::post('admin/saveExtraCSSJS', 'Admin@saveExtraCSSJS');

    // Admin config logins
    Route::get('admin/config-logins', 'Admin@configLogins');
    Route::post('admin/save-logins', 'Admin@saveLogins');

    Route::get('admin/configuration', 'Admin@configuration');
    Route::post('admin/configuration', 'Admin@configurationProcess');

    // Mail Server Configuration
    Route::get('admin/mailconfiguration', 'Admin@mailConfiguration');
    Route::post('admin/mailconfiguration', 'Admin@updateMailConfiguration');
    Route::get('admin/mailtest', 'Admin@mailtest');

    // Cloud settings
    Route::get('admin/cloud', 'Admin@cloudSettings');
    Route::post('admin/save-cloud-settings', 'Admin@saveCloudSettings');

    // Content Moderation
    Route::get('admin/moderation/{contentType}', 'Admin@moderateContent')->name('admin-moderate-content');
    Route::get('admin/content-moderation/delete-report/{id}', 'Admin@deleteContentReport')->name('admin-delete-content-report');

    // Site entry popup
    Route::get('admin/entry-popup', 'Admin@entryPopup');
    Route::post('admin/save/entry-popup', 'Admin@entryPopupSave');

    // Configure PWA
    Route::get('admin/pwa-config', 'Admin@configurePWA');
    Route::post('admin/pwa-store-config', 'Admin@savePWAConfiguration');

    // Configure Homepage Simulator
    Route::get('admin/simulator-config', 'Admin@simulatorConfig');
    Route::post('admin/simulator-store-config', 'Admin@saveSimulatorConfig');

});

// User Routes
Route::get('toprofile/{user_id}', function ($user_id) {
    $username = App\Profile::where('user_id', $user_id)->pluck('username')->first();
    if (is_null($username)) abort(404);
    return redirect(route('profile.show', ['username' => $username]));
})->name('profile.redirect');

Route::any('{username}', 'ProfileController@showProfile')->name('profile.show');
