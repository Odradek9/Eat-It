<?php


namespace App\Controllers;
use App\Foundation\admin\FCoupon;
use App\Foundation\FAddress;
use App\Foundation\FCart;
use App\Foundation\FCategory;
use App\Foundation\FFavourites;
use App\Foundation\FOrder;
use App\Foundation\FPaymentMethod;
use App\Foundation\FProduct;
use App\Foundation\FCustomer;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CreditCard;
use App\Models\Favourites;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Customer;
use App\Views\VUser;
use Pecee\SimpleRouter\SimpleRouter;

class CustomerController {

    public function getProfile() {
        $session=Session::getInstance();
        $forder= new FOrder();
        $fcustomer = new FCustomer();
        $customer = $session->loadUser();
        $orders = $forder->loadUsersOrders($customer->getId());
        $coupons = $fcustomer->loadUsersCoupons($customer->getId());
        $vUser = new VUser();
        $vUser->getProfile($customer, $orders, $coupons);

    }

    public function getId($id){
        $FUsers = new FCustomer();
        $user = $FUsers->load($id);
        $vUser = new VUser();
        $vUser->getCartId($user);
     }

    public function showAddCard(){
        $vUser = new VUser();
        $vUser->getAddCard();
    }

    public function showAddAddress(){
        $vUser = new VUser();
        $vUser->getAddAddress();
    }

    public function addCard(){
        $session=Session::getInstance();
        $fpay=new FPaymentMethod;
            $customer = $session->loadUser();
            $c=new CreditCard;
            $c->setNumber($_POST["number"]);
            $c->setCardHolder($_POST["holder"]);
            $c->setExpirationDate($_POST["date"]);
            $c->setCvv($_POST["cvv"]);
            $fpay->store($c, $customer->getId());
        redirect("/cart/checkout");
    }

    public function addAddress(){
        $session=Session::getInstance();
        $faddress=new FAddress;
            $customer = $session->loadUser();
            $a=new Address;
            $a->setCap($_POST["cap"]);
            $a->setCity($_POST["city"]);
            $a->setStreet($_POST["street"]);
            $a->setHomeNumber($_POST["number"]);
            $faddress->store($a, $customer->getId());
        redirect("/cart/checkout");
    }

}