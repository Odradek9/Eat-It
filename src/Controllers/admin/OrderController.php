<?php

namespace App\Controllers\admin;

use App\Foundation\FCustomer;
use App\Foundation\FOrder;
use App\Foundation\FProduct;
use App\Foundation\FAddress;
use App\Models\Order;
use App\Views\admin\VOrder;


class OrderController
{

    public function visualizeOrders()
    {
        $FOrder = new FOrder();
        $orders = $FOrder->getAll();
        $vorder = new VOrder();
        $vorder->getOrders($orders);
    }

    public function visualizeOrderDetails($id)
    {
        $forder = new FOrder();
        $fcustomer = new FCustomer();
        $faddress = new FAddress();
        $order = $forder->load($id);
        $customer = $fcustomer->load($order->getCustomerId());
        $products = $forder->getOrderProducts($id);
        $vorder = new VOrder();
        $vorder->getOrderDetails($order, $products, $customer);
    }

    public function acceptOrder($id)
    {
        $forder = new FOrder();
        $order = $forder->load($id);
        $order->setState("Accepted");
        $order->setArrivalTime(date('H:i:s', strtotime($_POST["arrival"])));
        $forder->update($order);
        redirect(url("/admin/orders"));
    }

    public function refuseOrder($id)
    {
        $forder = new FOrder();
        $order = $forder->load($id);
        $order->setState("Denied");
        $forder->update($order);
        redirect(url("/admin/orders"));
    }
}