<?php

use App\Http\Controllers\customer\customerscontroller;
use App\Http\Controllers\pricing\pricingcontroller;
use App\Http\Controllers\product\categorycontroller;
use App\Http\Controllers\product\productcontroller;
use App\Http\Controllers\supplier\suppliercontroller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes(['verify' => true]);
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
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', function () {
        return view('auth.login');

    });

});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth', 'verified'],
    ], function () {

        Route::group(['namespace' => 'Application_settings'], function () {
            //Place settings
            Route::resource('places_settings', 'place_settingsController');
            //country
            Route::resource('country', 'countryController');
            //city
            Route::get('/city/{id}', 'CityController@getgovernorate');
            Route::resource('city', 'CityController');
            //governorate
            Route::resource('governorate', 'governorateController');
            //area
            Route::get('/area/{id}', 'areaController@getcity');
            Route::resource('area', 'areaController');
            //settings_type settings
            Route::resource('settings_type', 'settings_typeController');
            // application_setting
            Route::resource('settings', 'application_settingsController');
            //currencies settings
            Route::resource('currencies', 'currenciesController');
            //nationalities settings
            Route::resource('nationalities_settings', 'nationalities_settingsController');
        });

        Route::group(['namespace' => 'dashbord'], function () {
            Route::resource('dashbord', 'dashbordController');
        });

        // ✅ Resource Route للعميل
        Route::resource('customers', customerscontroller::class)->names([
            'index' => 'customers.index',
            'create' => 'customers.create',
            'store' => 'customers.store',
            'show' => 'customers.show',
            'edit' => 'customers.edit',
            'update' => 'customers.update',
            'destroy' => 'customers.destroy',
        ]);

        Route::resource('suppliers', suppliercontroller::class)
            ->parameters(['suppliers' => 'supplier'])    // يضمن Binding صحيح
            ->names('suppliers');                        // أسماء routes: suppliers.index ..etc

        Route::prefix('products')->group(function () {
            Route::get('/', [productcontroller::class, 'index'])->name('products.index');
            Route::get('/create', [productcontroller::class, 'create'])->name('products.create');
            Route::get('/{id}/edit', [productcontroller::class, 'edit'])->name('products.edit');

            // categories (منفصلة داخل إدارة المنتجات)
            Route::get('/categories', [categorycontroller::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [categorycontroller::class, 'create'])->name('categories.create');
            Route::get('/categories/{id}/edit', [categorycontroller::class, 'edit'])->name('categories.edit');
        });

       // pricing 
        Route::prefix('pricing')->group(function () {
            Route::get('/lists', [pricingcontroller::class, 'index'])->name('pricing.lists.index');
            Route::get('/lists/create', [pricingcontroller::class, 'create'])->name('pricing.lists.create');
            Route::get('/lists/{id}/edit', [pricingcontroller::class, 'edit'])->name('pricing.lists.edit');
        });

        Route::get('/{page}', 'AdminController@index');

    });

Auth::routes();
//Auth::routes(['register' => false]);
