<?php

namespace App\Controllers\admin;

use App\Foundation\admin\FCoupon;
use App\Foundation\FRestaurant;
use App\Models\Coupon;
use App\Views\admin\VCoupon;
use App\Views\VStatistic;

class CouponController
{

    function index() {
        $vcoupon = new VCoupon();
        $coupons = new FCoupon();
        $vcoupon->showAll($coupons->getAll());
    }


}