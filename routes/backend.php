<?php


// SUPER ADMIN
Route::middleware('guest')->namespace('Backend')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('auth.login');
    Route::post('/login', 'Auth\LoginController@login');
});

Route::middleware(['auth:backend', 'backend.auth'])->namespace('Backend')->group(function () {

    Route::get('/dashboard', 'Dasboard\DasboardController@index')->name('dashboard');

    Route::post('/logout', 'Auth\LoginController@logout')->name('auth.logout');

    Route::group(['prefix' => 'profile', 'namespace' => 'User'], function () {
        Route::get('/password', 'ProfileController@password')->name('user.password');
        Route::post('/password', 'ProfileController@passwordChange')->name('user.passwordChange');
    });
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::get('/', 'UserController@index')->name('user');
        Route::any('/xhrIndex', 'UserController@xhrIndex')->name('user.xhrIndex');
        Route::get('/create', 'UserController@create')->name('user.create');
        Route::post('/store', 'UserController@store')->name('user.store');
        Route::get('{id}/edit', 'UserController@edit')->name('user.edit');
        Route::post('{id}/edit', 'UserController@update')->name('user.update');
        Route::post('{id}/delete', 'UserController@delete')->name('user.delete');
    });

    Route::group(['prefix' => 'restaurant', 'namespace' => 'Restaurant'], function () {
        Route::get('/', 'RestaurantController@index')->name('restaurant');
        Route::any('/xhrIndex', 'RestaurantController@xhrIndex')->name('restaurant.xhrIndex');
        Route::get('/create', 'RestaurantController@create')->name('restaurant.create');
        Route::post('/store', 'RestaurantController@store')->name('restaurant.store');
        Route::get('{id}/edit', 'RestaurantController@edit')->name('restaurant.edit');
        Route::post('{id}/edit', 'RestaurantController@update')->name('restaurant.update');
        Route::post('{id}/delete', 'RestaurantController@delete')->name('restaurant.delete');

        Route::get('{id}/view', 'RestaurantController@view')->name('restaurant.view');
    });

    Route::group(['prefix' => 'category', 'namespace' => 'Category'], function () {
        Route::any('/{restaurantId}/categoryXhr', 'CategoryController@xhrIndex')->name('category.xhrIndex');
        Route::post('/{restaurantId}', 'CategoryController@store')->name('category.store');
        Route::get('/{categoryId}/edit', 'CategoryController@edit')->name('category.edit');
        Route::post('/{categoryId}/edit', 'CategoryController@update')->name('category.update');
        Route::post('/{categoryId}/remove', 'CategoryController@remove')->name('category.remove');
        Route::post('/{categoryId}/products', 'CategoryController@products')->name('category.products');
    });

    Route::group(['prefix' => 'product', 'namespace' => 'Product'], function () {
        Route::post('/reOrder', 'ProductController@reOrder')->name('product.reOrder');
        Route::post('/{restaurantId}', 'ProductController@store')->name('product.store');
        Route::get('/{productId}/edit', 'ProductController@edit')->name('product.edit');
        Route::post('/{productId}/edit', 'ProductController@update')->name('product.update');
        Route::post('/{productId}/remove', 'ProductController@remove')->name('product.remove');
    });

    Route::group(['prefix' => 'menu', 'namespace' => 'Restaurant'], function () {
        Route::get('/', 'RestaurantController@menu')->name('menu');
        Route::any('/xhrMenu', 'RestaurantController@xhrMenu')->name('restaurant.xhrMenu');
    });

    Route::group(['prefix' => 'couponManagement', 'namespace' => 'Coupon'], function () {

        Route::get('/', 'CouponManagementController@index')->name('couponManagement.index');
        Route::any('/xhrIndex', 'CouponManagementController@xhrIndex')->name('couponManagement.xhrIndex');
        Route::any('/xhrRestaurantFilter', 'CouponManagementController@xhrRestaurantFilter')->name('couponManagement.xhrRestaurantFilter');


        Route::get('/createGroup', 'CouponManagementController@createGroup')->name('couponManagement.createGroup');
        Route::post('/createGroup', 'CouponManagementController@storeGroup')->name('couponManagement.storeGroup');
        Route::any('/{couponGroupId}/showGroup', 'CouponManagementController@showGroup')->name('couponManagement.showGroup');
        Route::post('/{couponGroupId}/removeGroup', 'CouponManagementController@removeGroup')->name('couponManagement.removeGroup');

        Route::get('/{couponGroupId}/editGroup', 'CouponManagementController@editGroup')->name('couponManagement.editGroup');
        Route::post('/{couponGroupId}/editGroup', 'CouponManagementController@updateGroup')->name('couponManagement.updateGroup');
        Route::get('/{couponGroupId}/restaurants', 'CouponManagementController@restaurant')->name('couponManagement.restaurant');
        Route::post('/{couponGroupId}/restaurants', 'CouponManagementController@restaurantUpdate')->name('couponManagement.restaurantUpdate');

        Route::get('/{couponGroupId}/createCoupon', 'CouponManagementController@createCoupon')->name('couponManagement.createCoupon');
        Route::post('/{couponGroupId}/createCoupon', 'CouponManagementController@storeCoupon')->name('couponManagement.storeCoupon');

        Route::post('/{couponId}/removeCoupon', 'CouponManagementController@removeCoupon')->name('couponManagement.removeCoupon');
    });


    Route::prefix('xhr')->name('xhr.')->namespace('Xhr')->group(function () {
        Route::get('/users', 'UserController@users')->name('users');
        Route::put('/user/status', 'UserController@statusUpdate')->name('users.statusUpdate');

        Route::get('/restaurants', 'RestaurantController@restaurants')->name('restaurants');
        Route::put('/restaurant/status', 'RestaurantController@statusUpdate')->name('restaurants.statusUpdate');

        Route::get('/products', 'ProductController@products')->name('products');
        Route::put('/product/status', 'ProductController@statusUpdate')->name('products.statusUpdate');
        Route::put('/product/price', 'ProductController@priceUpdate')->name('products.priceUpdate');
        Route::get('/product/search/{restaurantId}', 'ProductController@search')->name('products.search');

        Route::get('/categories', 'CategoryController@categories')->name('categories');
        Route::put('/category/status', 'CategoryController@statusUpdate')->name('categories.statusUpdate');

    });

});
