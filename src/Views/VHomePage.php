<?php

namespace App\Views;

class VHomePage
{
    public function viewHomePageIfLogged($favId, $cartId,  $bestSellers, $bestRateds, $bestReviews, $products) {
        $data=[
            'favId' => $favId,
            'cartId' => $cartId,
            'bestSellers'=> $bestSellers,
            'bestRateds'=> $bestRateds,
            'bestReviews'=> $bestReviews,
            'products' => $products,

        ];
        return setData("home", $data);
    }



    public function about(){
        return setData('aboutUs', []);
    }
}