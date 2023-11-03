<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CampaingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        // If authenticated, redirect to the dashboard page
        return redirect()->route('dashboard');
    }
// If not authenticated, show the login page
    return view('admin.page.auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.page.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\ProfileController::class,'show'])->name('profile-show');
    Route::post('/admin/profile/update', [\App\Http\Controllers\Admin\ProfileController::class,'update'])->name('profile-update');

    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::get('/add-zone',[ZoneController::class,'addZone'])->name('add.zone');
    Route::get('/list-zone',[ZoneController::class,'zonelist'])->name('list.zone');
    Route::post('/store/zone',[ZoneController::class,'store'])->name('zone.store');
    Route::delete('/zones/delete/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');
    Route::get('/zones/{id}/edit', [ZoneController::class,'zoneEdit'])->name('zones.edit');
    Route::put('/zones/{zone}', [ZoneController::class,'update'])->name('zones.update');
//    ajex
    Route::get('/api/zones/{zone}/categories', [ServiceController::class, 'getCategoriesByZone']);
    Route::get('/api/zones/{zone}/services', [ServiceController::class, 'getServicesByZone']);


    Route::get('/api/zones/{zone}/providers', [ServiceController::class, 'getProvidersByZone']);
    //Category
    Route::get('/add-category',[CategoryController::class,'categoryAdd'])->name('add.category');
    Route::get('/list-category',[CategoryController::class,'categoryList'])->name('list.category');
    Route::post('/store-categories', [CategoryController::class,'store'])->name('categories.store');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/category/edit/{id}',[CategoryController::class,'edit'])->name('category.edit');
    Route::put('/category/update/{id}',[CategoryController::class,'update'])->name('category.update');
    //Sub Category
    Route::get('/add-subcategory',[SubCategoryController::class,'subcategoryAdd'])->name('add.subcategory');
    Route::get('/list-subcategory',[SubCategoryController::class,'subcategoryList'])->name('list.subcategory');
    Route::post('/store-subcategories', [SubCategoryController::class,'subCategoryStore'])->name('subcategories.store');
    Route::delete('/subcategories/delete/{id}', [SubCategoryController::class, 'destroy'])->name('subcategories.destroy');
    Route::get('/subcategory/edit/{id}',[SubCategoryController::class,'edit'])->name('subcategory.edit');
    Route::put('/subcategory/update/{id}',[SubCategoryController::class,'update'])->name('subcategory.update');
    Route::get('/api/categories/{category}/subcategories', [CategoryController::class,'getSubcategories']);
    //service
    Route::get('/add-service',[ServiceController::class,'serviceAdd'])->name('add.service');
    Route::get('/list-service',[ServiceController::class,'list'])->name('list.service');
    Route::post('/store-service', [ServiceController::class,'Store'])->name('service.store');
    Route::delete('/service/delete/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
    Route::get('/service/edit/{id}',[ServiceController::class,'serviceEidt'])->name('service.edit');
    Route::put('/service/update/{id}',[ServiceController::class,'update'])->name('service.update');
    Route::delete('/service/delete/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
    Route::get('/service/details/{id}',[ServiceController::class,'details'])->name('service.details');


    Route::get('/Extra-service/add',[ServiceController::class,'extraAdd'])->name('add-extra');
    Route::get('/Extra-service/list',[ServiceController::class,'extraList'])->name('list-extra');
    Route::post('/Extra-service/store',[ServiceController::class,'storeExtraService'])->name('store-extra');
    Route::delete('/Extra-service/delete/{id}',[ServiceController::class,'destroyExtraService'])->name('delete-extra');
    Route::get('/extra-service/{id}/edit', [ServiceController::class,'edit'])->name('edit-extra');
    Route::put('extra-service/{id}/update', [ServiceController::class,'updateExtraService'])->name('update-extra');

    Route::get('/provider/add',[ProviderController::class,'providerAdd'])->name('provider.add');
    Route::post('/provider/store',[ProviderController::class,'providerStore'])->name('provider.store');
    Route::get('/provider/{slug?}',[ProviderController::class,'providerlist'])->name('provider.list');
    Route::delete('/provider/delete/{id}',[ProviderController::class,'delete'])->name('provider.delete');

    Route::get('/user/list',[CustomerController::class,'userList'])->name('user.list');

    Route::get('/campaign/add',[CampaingController::class,'add'])->name('campaign.add');
    Route::get('/service/campaign/add',[CampaingController::class,'serviceCampaignAdd'])->name('service.campaign.add');
    Route::get('/campaign/list',[CampaingController::class,'list'])->name('campaign.list');

    Route::post('/campaign/store',[CampaingController::class,'Store'])->name('campaign.store');
    Route::post('service/campaign/store',[CampaingController::class,'serviceStore'])->name('service.campaign.store');
    Route::get('/campaign/list',[CampaingController::class,'campaignlist'])->name('campaign.list');
    Route::delete('/campaign/delete/{id}', [CampaingController::class, 'destroy'])->name('campaign.destroy');

    Route::get('/coupon/add',[CouponController::class,'addCoupon'])->name('add-coupon');
    Route::get('/coupon/list',[CouponController::class,'listCoupon'])->name('coupon-list');
    Route::post('/coupon/store',[CouponController::class,'store'])->name('store-coupon');
    Route::delete('/coupons/{coupon}', [CouponController::class,'destroy'])->name('delete-coupon');
    Route::put('/coupon/update/{coupon}',[CouponController::class,'update'])->name('coupon-update');
    Route::get('/coupon/edit/{id}',[CouponController::class,'edit'])->name('coupon-edit');
    Route::get('/payment/list/{slug}',[PaymentController::class,'paymentList'])->name('payment-list');

    Route::get('/booking/list/{statusSlug}',[BookingController::class,'bookingList'])->name('booking.list');
    Route::get('/booking/details/{id}',[BookingController::class,'details'])->name('booking.details');
    Route::get('/invoice/{booking}',[BookingController::class,'invoice'])->name('invoice');
    Route::get('/download_invoice/{booking}',[BookingController::class,'downloadPDF'])->name('download-invoice');

    Route::get('/user/list',[CustomerController::class,'userList'])->name('user.list');

    Route::get('/page/setup/{slug}',[PageSettingController::class,'index'])->name('page-setting');
    Route::post('/page-settings/store', [PageSettingController::class, 'store'])->name('page-settings.store');


    Route::get('/get-booking-data', [DashboardController::class,'getBookingDataForChart'])->name('get.booking.data');
});

require __DIR__.'/auth.php';
