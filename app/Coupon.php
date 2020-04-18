<?php

namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    

    protected $table = 'coupons';

    public $timestamps = true;


    protected $fillable = [
        'coupon_name','coupon_id','coupon_link','coupon_amount','coupon_brand_id','coupon_type','coupon_value',
        'coupon_code','coupon_percent_off','expires_at',
    ];


    function users() {

        return $this->belongsToMany('App\User');
     }

    public function createNewCoupon($data) {

	    $dbInsert = $this->create($data);

	    return $dbInsert;

    }

    function getLatestCouponId() {

       return $this->latest('id')->first()['coupon_id'];
    }

    function adminGetCouponList() {

      return  $this->select('coupons.coupon_name','coupons.coupon_id','coupons.coupon_link','coupons.coupon_amount',
                            'coupons.expires_at','brands.brand_name','category.category_name')
                            ->leftJoin('brands', 'brands.id', '=', 'coupons.coupon_brand_id')
                            ->leftJoin('category', 'brands.brand_category_id', '=', 'category.id')       
                            ->where('coupons.is_used',0) 
                            ->groupBy('coupons.coupon_id')
                            ->orderBy('coupon_id', 'DESC')
                            ->get();
    }


    function editCouponBy($coupenId,$couponData) {
    

    	$result = $this->where('coupon_id',$coupenId)->update($couponData);

    	return($result);
    }

    function editVoucherBy($voucherId,$voucherData) {

    	$result = $this->where('id',$voucherId)->update($voucherData);

    	return($result);
    }

    function removeCoupon($couponId) {

        return  $this->where(['coupon_id'=>$couponId])->delete();
       
    }


    function removeVoucher($voucherId) {

        return  $this->where(['id'=>$voucherId])->delete();
        
    }


   function getVouchersGroupBy($couponId) {

      return  $this->where('coupon_id', $couponId)->get();
   }


}
