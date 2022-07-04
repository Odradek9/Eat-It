<?php

namespace App\Views\admin;

class VOrder {

    public function acceptOrders()
    {
        return view("admin/orders/orders", [
        ]);
    }

    public function getOrders($orders) {
        return view('admin/orders/orders', [
            'orders' => $orders
        ]);
    }
    public function getOrderDetails($order, $products, $customer) {
        return view('admin/orders/order-details', [
            "order"=>$order,
            "products"=>$products,
            "customer"=>$customer
        ]);
    }
}