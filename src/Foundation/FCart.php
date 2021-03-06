<?php

namespace App\Foundation;


use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWithQuantity;
use PDO;

class FCart extends FConnection {

    function __construct()
    {
        parent::__construct();
    }


    function store(): string {

        $pdo = FConnection::connect();
        $query = 'insert into carts () values ()';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    function update($newCart){
        $pdo = FConnection::connect();
        $query="select * from products_carts where cartId= :newCart";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':newCart'=>$newCart->getId()));
        $oldCart=$stmt->fetchAll();
        foreach ($newCart->getProducts() as $newp){
            $wasAlreadyPresent=false;
            foreach($oldCart as $p){
                if ($p[1]==$newp[0]->getId()){
                    $wasAlreadyPresent=true;
                }
            }
            if ($wasAlreadyPresent==true){
                $this->updateQuantity($newCart->getId(), $newp[0]->getId(), $newp[1]);
            }
            else {
                $this->addToCart($newCart, $newp[0], $newp[1]);
            }
        }
    }


    function addToCart($cart, $product,$quantity): string {
        $pdo = FConnection::connect();
        $query = 'insert into products_carts (`productId`, `cartId`, `quantity`) values(:id, :cartId, :quantity)';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':id'=>$product->getId(),
            ':cartId'=>$cart->getId(),
            ':quantity'=>$quantity
        ));
        return $pdo->lastInsertId();

    }

    function updateQuantity(int $cartId, int $productId, int $quantity) {
        $pdo = FConnection::connect();
        $query = 'UPDATE products_carts SET quantity = :quantity WHERE productId = :productId and cartId = :cartId;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':quantity'=>$quantity,
            ':productId'=>$productId,
            ':cartId'=>$cartId
        ));
    }

    function deleteFromCart($cart, $product) {
        $pdo = FConnection::connect();
        $query = 'delete from products_carts where productId = :id and cartId = :cartId;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':id'=>$product->getId(),
            ':cartId'=>$cart->getId()
        ));
    }



    public static function load($id) {
        $pdo = FConnection::connect();
        $query= 'select products.id, products.name, products.description, products.price, products_carts.quantity, products.imagePath from products  join products_carts ON products.id = products_carts.productId WHERE products_carts.cartId= :id;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
        $products = $stmt->fetchAll();
        $newCart = new Cart;
        $newCart->setId($id);
        $c=[];
        foreach($products as $product){
            $p= new Product;
            $p->setId($product[0]);
            $p->setName($product[1]);
            $p->setDescription($product[2]);
            $p->setPrice($product[3]);
            $p->setImagePath($product[5]);
            array_push($c, [$p, $product[4]]);
        }
        $newCart->setProducts($c);
        return $newCart;

    }


    function emptyCart($id){
        $pdo = FConnection::connect();
        $query="delete from products_carts where cartId= :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
    }
}


