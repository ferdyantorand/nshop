<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/aidanxnama', 'Frontend\HomeController@downlaodCatalog');
Route::get('/dev/test', 'HomeController@test')->name('dev.test');
Route::get('/login-register', 'Auth\RegisterController@loginRegister')->name('login.register');
Route::get('/session', 'Auth\RegisterController@deleteSession');
Route::get('/register-page', 'Auth\RegisterController@showRegistrationForm')->name('register-page');

Route::get('/', 'Frontend\HomeController@index')->name('home');
Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::post('/signin', 'Auth\LoginController@authenticate')->name('signin');
Route::get('/logout', 'Auth\LoginController@logoutUser')->name('logout');


Route::post('/search/', 'Frontend\ProductController@search')->name('search');
Route::get('/contact-us-form/', 'Frontend\HomeController@contactForm')->name('contact-form');
Route::post('/contact-us/', 'Frontend\HomeController@contact')->name('contact');
Route::post('/newsletter/', 'Frontend\HomeController@newsletter')->name('newsletter');
Route::get('others/about-us/', 'Frontend\HomeController@aboutUs')->name('about.us');
Route::get('others/{type}', 'Frontend\HomeController@Others')->name('others');
Route::post('/waiting-list/', 'Frontend\HomeController@waitingList')->name('waiting-list');

// product
Route::get('/product-list', 'Frontend\ProductController@index')->name('product.list');
Route::get('/product-detail/{product}', 'Frontend\ProductController@show')->name('product.detail');

// cart & transaction
Route::post('/add-cart', 'Frontend\CartController@addCart')->name('add.cart');
Route::get('/cart', 'Frontend\CartController@getCart')->name('cart');
Route::post('/submit-cart', 'Frontend\CartController@submitCart')->name('submit.cart');
Route::post('/delete-cart', 'Frontend\CartController@deleteCart')->name('delete.cart');
Route::post('/check-voucher', 'Frontend\CartController@voucherValidation')->name('check.voucher');

Route::get('/billing-shipment', 'Frontend\BillingController@getBilling')->name('billing');
Route::post('/submit-billing-shipment', 'Frontend\BillingController@submitBilling')->name('submit.billing');
Route::get('/checkout/{order}', 'Frontend\CheckoutController@getCheckout')->name('checkout');
Route::post('/submit-checkout', 'Frontend\CheckoutController@submitCheckout')->name('submit.checkout');

Route::get('/checkout-bank-information/{order}', 'Frontend\CheckoutController@TransferInformation')->name('checkout-transfer-information');
Route::get('/checkout-success/{order}', 'Frontend\CheckoutController@checkoutSuccess')->name('checkout-success');
Route::get('/checkout-failed/{order}', 'Frontend\CheckoutController@checkoutFailed')->name('checkout-failed');
Route::get('/checkout-paid/{order}', 'Frontend\CheckoutController@checkoutPaid')->name('checkout-paid');

Route::prefix('payment')->group(function(){
    Route::post('/checkout-notification', 'Frontend\MidtransController@notification')->name('notification.checkout');
    Route::get('/finish', 'Frontend\MidtransController@finish')->name('finish.checkout');
    Route::get('/unfinish', 'Frontend\MidtransController@unfinish')->name('unfinish.checkout');
    Route::get('/error', 'Frontend\MidtransController@error')->name('error.checkout');
});

Route::get('/orders', 'Frontend\OrderController@Index')->name('orders');
Route::get('/order/{order}', 'Frontend\OrderController@Show')->name('order.detail');
Route::get('/order/transfer_confirmation/{order}', 'Frontend\OrderController@ConfirmBankTransfer')->name('order.bank_confirmation');
Route::post('/order/transfer_confirmation', 'Frontend\OrderController@SubmitBankTransfer')->name('order.bank_submit');

// miscellaneous
Route::get('/test-location', 'Frontend\HomeController@getLocation')->name('getLocation');
Route::get('/test-province', 'Frontend\HomeController@getProvince')->name('getProvince');
Route::get('/testing-func', 'Frontend\HomeController@TestingPurpose')->name('testing');


// ADMIN ROUTE
// ====================================================================================================================

Route::prefix('admin')->group(function(){
    Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    // Admin User
    Route::get('/admin-users', 'Admin\AdminUserController@index')->name('admin.admin-users.index');
    Route::get('/admin-users/create', 'Admin\AdminUserController@create')->name('admin.admin-users.create');
    Route::post('/admin-users/store', 'Admin\AdminUserController@store')->name('admin.admin-users.store');
    Route::get('/admin-users/edit/{item}', 'Admin\AdminUserController@edit')->name('admin.admin-users.edit');
    Route::post('/admin-users/update', 'Admin\AdminUserController@update')->name('admin.admin-users.update');
    Route::post('/admin-users/delete', 'Admin\AdminUserController@destroy')->name('admin.admin-users.destroy');

    // User
    Route::get('/users', 'Admin\UserController@index')->name('admin.users.index');
    Route::get('/users/create', 'Admin\UserController@create')->name('admin.users.create');
    Route::post('/users/store', 'Admin\UserController@store')->name('admin.users.store');
    Route::get('/users/edit/{item}', 'Admin\UserController@edit')->name('admin.users.edit');
    Route::post('/users/update', 'Admin\UserController@update')->name('admin.users.update');
    Route::post('/users/delete', 'Admin\UserController@destroy')->name('admin.users.destroy');

    // Category
    Route::get('/categories', 'Admin\CategoryController@index')->name('admin.categories.index');
    Route::get('/categories/create', 'Admin\CategoryController@create')->name('admin.categories.create');
    Route::post('/categories/store', 'Admin\CategoryController@store')->name('admin.categories.store');
    Route::get('/categories/edit/{item}', 'Admin\CategoryController@edit')->name('admin.categories.edit');
    Route::post('/categories/update', 'Admin\CategoryController@update')->name('admin.categories.update');
    Route::post('/categories/delete', 'Admin\CategoryController@destroy')->name('admin.categories.destroy');

    // Currency
    Route::get('/currencies', 'Admin\CurrencyController@index')->name('admin.currencies.index');

    // Contact Message
    Route::get('/contact-messages', 'Admin\ContactMessageController@index')->name('admin.contact-messages.index');

    // Subscribes
    Route::get('/subscribes', 'Admin\SubscribeController@index')->name('admin.subscribes.index');

    // Waiting Lists
    Route::get('/waitinglists', 'Admin\WaitingListController@index')->name('admin.waitinglists.index');
    Route::get('/waitinglist/export', 'Admin\WaitingListController@downloadList')->name('admin.waitinglists.download');

    // Store Address
    Route::get('/store-address', 'Admin\StoreAddressController@index')->name('admin.store-address.index');
    Route::get('/store-address/create', 'Admin\StoreAddressController@create')->name('admin.store-address.create');
    Route::post('/store-address/delete', 'Admin\StoreAddressController@destroy')->name('admin.store-address.destroy');

    // Voucher
    Route::get('/vouchers/', 'Admin\VoucherController@index')->name('admin.vouchers.index');
    Route::get('/vouchers/show/{item}', 'Admin\VoucherController@show')->name('admin.vouchers.show');
    Route::get('/vouchers/create', 'Admin\VoucherController@create')->name('admin.vouchers.create');
    Route::get('/vouchers/create/product', 'Admin\VoucherController@createProduct')->name('admin.vouchers.create-product');
    Route::post('/vouchers/store', 'Admin\VoucherController@store')->name('admin.vouchers.store');
    Route::get('/vouchers/edit/category/{item}', 'Admin\VoucherController@editCategory')->name('admin.vouchers.edit-category');
    Route::get('/vouchers/edit/product/{item}', 'Admin\VoucherController@editProduct')->name('admin.vouchers.edit-product');
    Route::post('/vouchers/update', 'Admin\VoucherController@update')->name('admin.vouchers.update');
    Route::post('/vouchers/delete', 'Admin\VoucherController@destroy')->name('admin.vouchers.destroy');

    // FAQ
    Route::get('/faqs', 'Admin\FaqController@index')->name('admin.faqs.index');
    Route::get('/faqs/create', 'Admin\FaqController@create')->name('admin.faqs.create');
    Route::post('/faqs/store', 'Admin\FaqController@store')->name('admin.faqs.store');
    Route::get('/faqs/edit/{item}', 'Admin\FaqController@edit')->name('admin.faqs.edit');
    Route::post('/faqs/update', 'Admin\FaqController@update')->name('admin.faqs.update');
    Route::post('/faqs/delete', 'Admin\FaqController@destroy')->name('admin.faqs.destroy');

    // Product
    Route::get('/product/', 'Admin\ProductController@index')->name('admin.product.index');
    Route::get('/product/show/{item}', 'Admin\ProductController@show')->name('admin.product.show');
    Route::get('/product/create', 'Admin\ProductController@create')->name('admin.product.create');
    Route::get('/product/create-copy/{item}', 'Admin\ProductController@createCopyProduct')->name('admin.product.create-copy');
    Route::post('/product/store', 'Admin\ProductController@store')->name('admin.product.store');

    Route::get('/product/create-customize/{item}', 'Admin\ProductController@createCustomize')->name('admin.product.create.customize');
    Route::post('/product/store-customize/{item}', 'Admin\ProductController@storeCustomize')->name('admin.product.store.customize');
    Route::get('/product/edit-customize/{item}', 'Admin\ProductController@editCustomize')->name('admin.product.edit.customize');
    Route::post('/product/update-customize/{item}', 'Admin\ProductController@updateCustomize')->name('admin.product.update.customize');
    Route::get('/product/edit/{item}', 'Admin\ProductController@edit')->name('admin.product.edit');
    Route::post('/product/update', 'Admin\ProductController@update')->name('admin.product.update');
    Route::post('/product/delete', 'Admin\ProductController@destroy')->name('admin.product.destroy');


    // Orders
    Route::get('/orders', 'Admin\OrderController@index')->name('admin.orders.index');
    Route::get('/orders/detail/{item}', 'Admin\OrderController@show')->name('admin.orders.detail');
    Route::post('/orders/tracking-code/', 'Admin\OrderController@storeTracking')->name('admin.orders.tracking');
    Route::get('/orders/processing', 'Admin\OrderController@indexProcessing')->name('admin.orders.processing');
    Route::get('/orders/shipped', 'Admin\OrderController@indexShipped')->name('admin.orders.shipped');
    Route::get('/orders/bank-transfer', 'Admin\OrderController@indexBankTransfer')->name('admin.orders.bank_transfer');
    Route::post('/orders/bank-transfer-accept/', 'Admin\OrderController@acceptBankTransfer')->name('admin.orders.bank_transfer_accept');
    Route::get('/orders/print_packing/{id}', 'Admin\OrderController@packingLabel')->name('admin.orders.print_packing');

    //create new transaction
    Route::get('/orders/create', 'Admin\OrderController@create')->name('admin.orders.create');
    Route::post('/orders/store', 'Admin\OrderController@store')->name('admin.orders.store');
    Route::get('/orders/destroy/{id}', 'Admin\OrderController@destroy')->name('admin.orders.destroy');
});

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

Route::view('/send-email', 'auth.send-email');

Route::get('/zoho', 'Admin\AdminController@test');

// Datatables
Route::get('/datatables-admin-users', 'Admin\AdminUserController@getIndex')->name('datatables.admin_users');
Route::get('/datatables-admin-products', 'Admin\ProductController@getIndex')->name('datatables.admin_products');
Route::get('/datatables-users', 'Admin\UserController@getIndex')->name('datatables.users');
Route::get('/datatables-categories', 'Admin\CategoryController@getIndex')->name('datatables.categories');
Route::get('/datatables-currencies', 'Admin\CurrencyController@getIndex')->name('datatables.currencies');
Route::get('/datatables-store-addresses', 'Admin\StoreAddressController@getIndex')->name('datatables.store-addresses');
Route::get('/datatables-contact-message', 'Admin\ContactMessageController@getIndex')->name('datatables.contact-message');
Route::get('/datatables-subscribes', 'Admin\SubscribeController@getIndex')->name('datatables.subscribes');
Route::get('/datatables-waitinglists', 'Admin\WaitingListController@getIndex')->name('datatables.waitinglists');
Route::get('/datatables-vouchers', 'Admin\VoucherController@getIndex')->name('datatables.vouchers');
Route::get('/datatables-faqs', 'Admin\FaqController@getIndex')->name('datatables.faqs');
Route::get('/datatables-orders', 'Admin\OrderController@getIndex')->name('datatables.orders');
Route::get('/datatables-order-bank-trfs', 'Admin\OrderController@getIndexBankTransfer')->name('datatables.order-bank-trfs');
Route::get('/datatables-order-processing', 'Admin\OrderController@getIndexProcessing')->name('datatables.order-processing');
Route::get('/datatables-order-shipped', 'Admin\OrderController@getIndexShipped')->name('datatables.order-shipped');

// Select2
Route::get('/select-roles', 'Admin\RoleController@getRoles')->name('select.roles');
Route::get('/select-categories', 'Admin\CategoryController@getCategories')->name('select.categories');
Route::get('/select-products', 'Admin\ProductController@getProducts')->name('select.products');
Route::get('/select-product-weights', 'Admin\ProductController@getProductWithWeights')->name('select.product-weights');
Route::get('/select-product-positions', 'Admin\ProductController@getProductPositions')->name('select.product-positions');

// Third Party API
Route::get('/update-currency', 'Admin\CurrencyController@getCurrenciesUpdate')->name('update-currencies');

// Email Aauth
Route::get('/request-verification/{email}', 'Auth\RegisterController@RequestVerification')->name('request-verification');

// Rajaongkir AJAX
Route::post('/ajax/rajaongkir/cost', 'Frontend\RajaOngkirController@getCost')->name('ajax.rajaongkir.cost');
Route::post('/ajax/rajaongkir/waybill', 'Frontend\RajaOngkirController@getWaybill')->name('ajax.rajaongkir.waybill');
