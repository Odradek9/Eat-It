<?php

namespace App\Foundation;

use App\Models\Address;

class FAddress extends FConnection{

    function __construct()
    {
        parent::__construct();
    }
    public function load($id){
        $pdo = FConnection::connect();
        $query = 'select * from shippingaddresses where id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(":id"=>$id));
        $address = $stmt->fetch();
        $add = new Address();
        $add->setId($address[0]);
        $add->setCap($address[1]);
        $add->setCity($address[2]);
        $add->setStreet($address[3]);
        $add->setHomeNumber($address[4]);
        return $add;
    }

    public function loadFromCustomerId($id){

        $pdo = FConnection::connect();
        $query = 'select * from shippingaddresses where customerId= :id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(":id"=>$id));
        $addresses=$stmt->fetchAll();
        $a=[];
        foreach ($addresses as $address){
        $add=new Address();
        $add->setId($address[0]);
        $add->setCap($address[1]);
        $add->setCity($address[2]);
        $add->setStreet($address[3]);
        $add->setHomeNumber($address[4]);
        array_push($a, $add);
        }
        return $a;
    }

    public function exist($id){
        $pdo = FConnection::connect();
        $query = 'select * from shippingaddresses where id=:id';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(":id"=>$id));
        $address=$stmt->fetch();
        if ($address!=NULL){
            return true;
        }
        else{
            return false;
        }
    }

    public function store($address, $customerid){
        $pdo = FConnection::connect();
        $cap=$address->getCap();
        $city=$address->getCity();
        $street=$address->getStreet();
        $homenumber=$address->getHomeNumber();
        $query="INSERT INTO `shippingaddresses` (`cap`, `city`, `street`, `homeNumber`, `customerId`) VALUES (:cap, :city, :street, :homenumber, :customerId)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':cap'=>$cap,
            'city'=>$city,
            'street'=>$street,
            'homenumber'=>$homenumber,
            'customerId'=>$customerid));
    }



    public function delete($id){
        $pdo = FConnection::connect();
        $query="delete from shippingaddresses where id=:id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
    }

}