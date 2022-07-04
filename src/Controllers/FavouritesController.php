<?php

namespace App\Controllers;


use App\Foundation\FCart;
use App\Foundation\FCategory;
use App\Foundation\FFavourites;
use App\Foundation\FProduct;
use App\Models\Favourites;
use App\Views\VFavourites;
use App\Views\VProduct;

class FavouritesController {



    public function getFavouritesProducts() {
        $session = Session::getInstance();
        $FFavourites = new FFavourites();
        $cus = $session->loadUser();
        $favId = $cus->getFav()->getId();
        $products =  $FFavourites->load($favId)->getProducts();
        $vFavourites = new VFavourites();
        $vFavourites->viewFavouritesProducts($favId, $products);
    }


    public function deleteProductFromFav() {
        $session = Session::getInstance();
        $FFavourites = new FFavourites();
        $productId = $_POST['productId'];

        $cus = $session->loadUser();
        $favId = $cus->getFav()->getId();
        if ($_POST['option'] == 'delete') {
            $FFavourites->deleteFromFavourites($favId, $productId);
        }
        redirect(url('/favourites', ['favId' => $favId]));
    }


    public function addToCartFromFav($cartId) {
        $session = Session::getInstance();
        $fProduct = new FProduct();
        $fCart = new FCart();
        $productId = $_POST['productId'];

        $cus = $session->loadUser();
        $favId = $cus->getFav()->getId();
        $cart = $fCart->load($cartId);
        $product = $fProduct->load($productId);
        $cart->addToCart($product, 1);
        $cus->setCart($cart);
        $fCart->update($cart);
        $session->saveUserInSession($cus);
        redirect(url('/favourites', ['favId' => $favId]));
    }

}