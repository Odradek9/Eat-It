<?php

namespace App\Controllers;

use App\Foundation\FCart;
use App\Foundation\FOrder;
use App\Foundation\FProduct;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Views\VCart;
use PDO;

class CartController {

    public function getProductsOfCart() {
        $session = Session::getInstance();
        $FCart = new FCart();
        $cus = $session->loadUser();
        $cartId = $cus->getCart()->getId();
        $cart = $FCart->load($cartId);
        $products = $cart->getProducts();
        $total = 0;
        foreach ($products as $p) {
            $total = $total + $p[0]->getPrice() * $p[1];
        }
        $vCart = new VCart();
        $vCart->getProducts($cart, $total, $products);
    }



    public function create() {
        $FCart = new FCart();
        $cart = new Cart();
        $cart->setId(NULL);
        $FCart->store($cart);
    }

    public function updateQuantity() {
        $session = Session::getInstance();
        $fCart = new Fcart();
        $FProduct = new FProduct();
        $cus = $session->loadUser();
        $cartId = $cus->getCart()->getId();
        $cart = $fCart->load($cartId);
        $productId = $_POST['productId'];
        $product = $FProduct->load($productId);
        if ($_POST['option'] == 'plus') {
            $cart->addToCart($product, 1);
        } else {
            $cart->addToCart($product, -1);
        }
        $cus->setCart($cart);
        $fCart->update($cart);
        $session->saveUserInSession($cus);
        redirect(url('productsOfCarts', ['cartId' => $cartId]));
    }


    public function destroy() {
        $session = Session::getInstance();
        $FCart = new FCart();
        $FProduct = new FProduct();
        $cus = $session->loadUser();
        $cartId = $cus->getCart()->getId();
        $cart = $FCart->load($cartId);
        $productId = $_POST['productId'];
        $product = $FProduct->load($productId);
        if ($_POST['option'] == 'delete') {
            $cart->deleteFromCart($product);
            $FCart->deleteFromCart($cart, $product);
        }
        $cus->setCart($cart);
        $session->saveUserInSession($cus);
        redirect(url('productsOfCarts', ['cartId' => $cartId]));
    }

}





