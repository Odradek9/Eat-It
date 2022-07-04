<?php

namespace App\Foundation\admin;

use App\Foundation\FConnection;
use App\Models\Product;

class FProduct
{

    function store($product, $category): string
    {
        $pdo = FConnection::connect();
        $query = "INSERT INTO products(`name`, `description`, `price`, `categoryId`, `imagePath`, `timesOrdered`) VALUES (:name, :description, :price, :id, :image, :timesOrdered);";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':name'=>$product->getName(),
            ':description'=>$product->getDescription(),
            ':price'=>$product->getPrice(),
            ':id'=>$category->getId(),
            ':image'=>$product->getImagePath(),
            ':timesOrdered'=>$product->getTimesOrdered()
        ));
        return $pdo->lastInsertId();

    }

    function getBestSeller(){
        $pdo = FConnection::connect();
        $query="SELECT * FROM products ORDER BY timesOrdered DESC LIMIT 1;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $prod = $stmt->fetch();
            $p = new Product();
            $p->setId($prod[0]);
            $p->setName($prod[1]);
            $p->setDescription($prod[2]);
            $p->setPrice($prod[3]);
            $p->setImagePath($prod[5]);
            $p->setTimesOrdered($prod[6]);
        return $p;
    }

    function getWorstSeller(){
        $pdo = FConnection::connect();
        $query="SELECT * FROM products ORDER BY timesOrdered LIMIT 1;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $prod = $stmt->fetch();
        $p = new Product();
        $p->setId($prod[0]);
        $p->setName($prod[1]);
        $p->setDescription($prod[2]);
        $p->setPrice($prod[3]);
        $p->setImagePath($prod[5]);
        $p->setTimesOrdered($prod[6]);
        return $p;
    }


    function getAll() {
        $pdo = FConnection::connect();
        $query= "SELECT * FROM products";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $prods = $stmt->fetchAll();
        $products= [];
        foreach ($prods as $prod) {
            $p = new Product();
            $p->setId($prod[0]);
            $p->setName($prod[1]);
            $p->setDescription($prod[2]);
            $p->setPrice($prod[3]);
            $p->setImagePath($prod[5]);
            array_push($products, $p);
        }
        return $products;
    }
}