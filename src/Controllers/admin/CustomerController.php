<?php

namespace App\Controllers\admin;

use App\Foundation\admin\FCoupon;
use App\Foundation\admin\FCustomer;
use App\Models\Coupon;
use App\Views\admin\VCustomer;

class CustomerController
{

    function index() {
        $vCustomer = new VCustomer();
        $fCustomer = new FCustomer();

        return $vCustomer->showAll($fCustomer->getAll());
    }


    public function sendCoupon() {
        $fCoupon = new FCoupon;
        $customersId = $_POST['customers'];
        $pricecut = $_POST['pricecut'];
        $expiration = $_POST['expiration'];
        if (validate($_POST, [
            "expiration"=>["isNotExpired"]
        ])){
            foreach ($customersId as $c) {
                $coupon = new Coupon();
                $coupon->setPriceCut($pricecut);
                $coupon->setExpirationDate($expiration);
                $fCoupon->store($coupon, $c);
            }
            redirect(url('showAllCustomers'));
        }
       else{
           redirect(url('showBest'));
       }
    }


    public function showBest() {
        $vCustomer = new VCustomer();
        $fCustomers = new FCustomer();
        $previousMonth = date('m') - 1;
        return $vCustomer->showBest( $fCustomers->topTenCustomersByTotal($previousMonth));
    }

}