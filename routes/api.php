<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('user/login','UserController@userLogin');


Route::post('coupon','CouponController@storeCoupon')->middleware('auth:api') ;
Route::get('coupon','CouponController@getcouponList')->middleware('auth:api');
Route::patch('coupon/{couponId}/edit','CouponController@editCoupon') ->middleware('auth:api') ;
Route::delete('coupon/{couponId}','CouponController@deleteCoupon')->middleware('auth:api');

Route::get('coupon/voucher/{couponId}','CouponController@getCopenCodes')->middleware('auth:api');
Route::patch('coupon/voucher/{voucherId}/edit','CouponController@editVoucher')->middleware('auth:api') ;
Route::delete('coupon/voucher/{voucherId}','CouponController@deleteVoucher')->middleware('auth:api') ;

Route::get('user/coupon','CouponController@geUserVoucherList');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
