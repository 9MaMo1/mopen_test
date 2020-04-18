<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\User;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    private $coupon;

    public function __construct()
    {

        $this->coupon = new Coupon();
        $this->user = new User();

    }
/*
store coupon method
 */

    public function storeCoupon(Request $request)
    {

        /*
        $request->validate([

        'coupon_name'        => ['required','string','max:255'],
        'coupon_link'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'coupon_amount'      => ['required', 'integer'],
        'coupon_brand_id'    => ['required', 'integer'],
        'coupon_code'        => ['nullable', 'integer'],
        'coupon_type'        => ['required' ,  Rule::in(['voucher_code', 'discounted']),],
        'coupon_value'       => ['nullable', 'integer'],
        'coupon_percent_off' => ['nullable', 'integer'],
        'expires_at'         => ['nullable'],

        ]);  */

        $couponType = request('coupon_type');

        $couponData = [
            'coupon_name' => request('coupon_name'),
            'coupon_link' => request('coupon_link'),
            'coupon_amount' => request('coupon_amount'),
            'coupon_code' => request('coupon_code'),
            'coupon_brand_id' => request('coupon_brand_id'),
            'coupon_type' => $couponType,
            'coupon_value' => request('coupon_value'),
            'coupon_percent_off' => request('coupon_percent_off'),
            'expires_at' => request('expires_at'),
        ];

        $users = $this->user->getUsersList();
        $latestCouponId = $this->coupon->getLatestCouponId();
        $couponData['coupon_id'] = $latestCouponId + 1;

        $vouchersData = $this->voucherCodeManager($couponType, $request);

        $hasUploadedCode = $vouchersData['hasUploadedCode'];
        $voucherCodesArr = $vouchersData['voucherCodesArr'];

        $this->createAndUpdate([
            'voucherCodesArr' => $voucherCodesArr,
            'couponData' => $couponData,
            'hasUploadedCode' => $hasUploadedCode,
            'users' => $users,
            'mode' => 'create',
        ]);

        return response()->json(['success' => 1, 'message' => 'data has been created'], 200);
    }

    /*
    get coupon list by admin permision,
    grouped by coupon_id
     */

    public function getcouponList()
    {

        $user = auth('api')->user();

        if ($user['admin']) {
            $list = $this->coupon->adminGetCouponList();
        }

        return response()->json(['success' => 1, 'data' => $list], 200);
    }

    /*
    edit coupon by coupon_id

     */

    public function editCoupon($couponId, Request $request)
    {

        /*
        $request->validate([

        'coupon_name'        => ['required','string','max:255'],
        'coupon_link'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'coupon_amount'      => ['required', 'integer'],
        'coupon_brand_id'    => ['required', 'integer'],
        'coupon_code'        => ['nullable', 'integer'],
        'coupon_type'        => ['required' ,  Rule::in(['voucher_code', 'discounted']),],
        'coupon_value'       => ['nullable', 'integer'],
        'coupon_percent_off' => ['nullable', 'integer'],
        'expires_at'         => ['nullable'],

        ]);  */

        $couponType = request('coupon_type');

        $couponData = [
            'coupon_name' => request('coupon_name'),
            'coupon_link' => request('coupon_link'),
            'coupon_amount' => request('coupon_amount'),
            'coupon_code' => request('coupon_code'),
            'coupon_brand_id' => request('coupon_brand_id'),
            'coupon_type' => $couponType,
            'coupon_value' => request('coupon_value'),
            'coupon_percent_off' => request('coupon_percent_off'),
            'expires_at' => request('expires_at'),
        ];

        $vouchersData = $this->voucherCodeManager($couponType, $request);

        $hasUploadedCode = $vouchersData['hasUploadedCode'];
        $voucherCodesArr = $vouchersData['voucherCodesArr'];

        $this->createAndUpdate(['couponId' => $couponId,
            'voucherCodesArr' => $voucherCodesArr,
            'couponData' => $couponData,
            'hasUploadedCode' => $hasUploadedCode,
            'mode' => 'update']);

        return response()->json(['success' => 1, 'message' => 'data has been updated'], 200);

    }

/*
edit voucher by id

 */

    public function editVoucher($voucherId)
    {

        /*
        $request->validate([

        'coupon_name'        => ['required','string','max:255'],
        'coupon_link'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'coupon_code'       => ['nullable', 'integer'],
        'coupon_brand_id'    => ['required', 'integer'],
        'coupon_value'       => ['nullable', 'integer'],
        'coupon_percent_off' => ['nullable', 'integer'],
        'expires_at'         => ['nullable'],

        ]);  */

        $couponData = [
            'coupon_name' => request('coupon_name'),
            'coupon_link' => request('coupon_link'),
            'coupon_code' => request('coupon_code'),
            'coupon_brand_id' => request('coupon_brand_id'),
            'coupon_value' => request('coupon_value'),
            'coupon_percent_off' => request('coupon_percent_off'),
            'expires_at' => request('expires_at'),
        ];

        $this->coupon->editVoucherBy($voucherId, $couponData);

        return response()->json(['success' => 1, 'message' => 'data has been updated'], 200);
    }

    /*
    get specific copen details by coupon_id

     */

    public function getCopenCodes($coupenId)
    {

        $result = $this->coupon->getVouchersGroupBy($coupenId);
        return response()->json(['success' => 1, 'data' => $result], 200);
    }

    public function geUserVoucherList()
    {

        $user = auth('api')->user();
        if ($user['role']) {
            $list = $user->coupons->toArray();
        }

        return response()->json(['success' => 1, 'data' => $list], 200);
    }

    /*
    remove coupon by coupon_id
     */
    public function deleteCoupon($voucherId)
    {

        $this->coupon->removeCoupon($voucherId);
        return response()->json(['success' => 1], 200);

    }

    /*
    remove voucher by voucherId
     */
    public function deleteVoucher($voucherId)
    {

        $this->coupon->removeVoucher($voucherId);
        return response()->json(['success' => 1], 200);

    }

    /*
    check coucher code
    upload text file
    return voucher array
     */
    private function voucherCodeManager($couponType, $request)
    {

        $hasUploadedCode = false;
        $voucherCodesArr = [];
        if ($couponType == 'voucher_code') {

            if ($request->hasFile('upload_code')) {

                $fileName = "voucherCodes.txt";
                $request->upload_code->storeAs('voucherCodes', $fileName);

                $voucherCodes = file_get_contents(storage_path('app/voucherCodes/voucherCodes.txt'));
                $voucherCodesArr = preg_split("/\r\n|\n|\r/", $voucherCodes);

                $hasUploadedCode = true;
            }
        }

        return ['hasUploadedCode' => $hasUploadedCode,
            'voucherCodesArr' => $voucherCodesArr];
    }

    /*
    create and update by mode

     */
    private function createAndUpdate($data)
    {

        $voucherCodesArr = $data['voucherCodesArr'] ?? [];
        $couponData = $data['couponData'];
        $users = $data['users'] ?? '';
        $mode = $data['mode'];
        $hasUploadedCode = $data['hasUploadedCode'];
        $coupon_amount = $couponData['coupon_amount'];

        for ($i = 0; $i < $coupon_amount; $i++) {

            if ($hasUploadedCode) {
                $couponData['coupon_code'] = $voucherCodesArr[$i];
            }

            switch ($mode) {

                case 'create':

                    $insertedCoupen = $this->coupon->createNewCoupon($couponData);
                    $insertedCoupen->users()->attach($users[$i]['id']);

                    break;
                case 'update':

                    $this->coupon->editCouponBy($data['couponId'], $couponData);

                    break;
            }

        }
    }

}
