<?php


namespace App\Foundation;


use App\Models\Customer;
use App\Models\Product;
use App\Models\Review;
use PDO;

class FProduct extends FConnection {

    function __construct()
    {
        parent::__construct();
    }

    function store($product, $categoryId): string {
        $pdo = FConnection::connect();
        $query = 'INSERT INTO `products`(`name`, `description`, `price`, `categoryId`, `imagePath`, `timesOrdered`) VALUES (:name, :description, :price, :categoryId, :image, 0);';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':name'=>$product->getName(),
            ':description'=> $product->getDescription(),
            ':price'=>  $product->getPrice(),
            ':categoryId' => $categoryId,
            ':image'=>$product->getImagePath()
        ));
        return $pdo->lastInsertId();
    }

    function load($productId){
        $pdo = FConnection::connect();
        $query = 'select * from products where id = :productId';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':productId'=>$productId));
        $p=$stmt->fetch();
        $freviews= new FReview();
        $prod=new Product;
        $prod->setId($p[0]);
        $prod->setName($p[1]);
        $prod->setDescription($p[2]);
        $prod->setPrice($p[3]);
        $prod->setImagePath($p[5]);
        $prod->setTimesOrdered($p[6]);
        $prod->setReviews($freviews->loadReviewsOfProduct($p[0]));
        return $prod;
    }


    function getBestSellers(){
        $pdo = FConnection::connect();
        $query="SELECT * FROM products ORDER BY timesOrdered DESC LIMIT 8;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $prods = $stmt->fetchAll();
        $products = [];
        foreach ($prods as $prod) {
            $p = new Product();
            $p->setId($prod[0]);
            $p->setName($prod[1]);
            $p->setDescription($prod[2]);
            $p->setPrice($prod[3]);
            $p->setImagePath($prod[5]);
            $p->setTimesOrdered($prod[6]);
            array_push($products, $p);

        }
        return $products;
    }


    function getAvgRating($productId){
        $pdo = FConnection::connect();
        $query="SELECT avg(stars) as avgstars FROM reviews where ProductId = :productId;";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':productId'=>$productId));
        return $stmt->fetchAll();

    }

    function getBestRated(){
        $pdo = FConnection::connect();
        $query="select avg(stars) as average, p.id, p.name, p.price, p.imagePath from reviews as r join products as p on p.id = r.productId group by productId order by average desc limit 8";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getRatings($productId){
        $pdo = FConnection::connect();

        $query='select * from reviews where productId = :productId';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':productId'=>$productId));
        $reviews = $stmt->fetchAll();
        $rev = [];

        foreach($reviews as $review) {
            $r = new Review();
            $customer = new FCustomer();
            $r->setId($review[0]);
            $r->setStars($review[1]);
            $r->setComment($review[2]);
            $r->setCustomer($customer->load($review[3]));
            array_push($rev, $r);
        }
        return $rev;
    }


    function getBestReviews(){
        $pdo = FConnection::connect();
        $query="SELECT r.id, r.stars, r.comment, r.customerId, r.productId, p.name, p.imagePath FROM reviews as r join products as p on r.productId = p.id group by p.id ORDER BY stars DESC LIMIT 6;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $reviews = $stmt->fetchAll();
        $rev = [];
        $prod = [];

        foreach($reviews as $review) {
            $r = new Review();
            $p = new Product();
            $customer = new FCustomer();
            $r->setId($review[0]);
            $r->setStars($review[1]);
            $r->setComment($review[2]);
            $r->setCustomer($customer->load($review[3]));
            array_push($rev, $r);
            $p->setId($review[4]);
            $p->setName($review[5]);
            $p->setImagePath($review[6]);
            $p->setReviews($rev);
            array_push($prod, $p);
            $rev = [];
        }
        return $prod;
    }


    function getBestSellerOfCategory($id){
        $pdo = FConnection::connect();
        $query="SELECT * FROM products WHERE categoryId = :id ORDER BY timesOrdered DESC LIMIT 1;";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
        $p=$stmt->fetch();
        if($p!=NULL){
        $prod=new Product;
        $prod->setId($p[0]);
        $prod->setName($p[1]);
        $prod->setDescription($p[2]);
        $prod->setPrice($p[3]);
        $prod->setImagePath($p[5]);
        $prod->setTimesOrdered($p[6]);
        return $prod;
        }
        else{return NULL;}
    }

    function getWorstSellerOfCategory($id){
        $pdo = FConnection::connect();
        $query="SELECT * FROM products WHERE categoryId = :id ORDER BY timesOrdered LIMIT 1;";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
        $p=$stmt->fetch();
        if($p!=NULL){
        $prod=new Product;
        $prod->setId($p[0]);
        $prod->setName($p[1]);
        $prod->setDescription($p[2]);
        $prod->setPrice($p[3]);
        $prod->setImagePath($p[5]);
        $prod->setTimesOrdered($p[6]);
        return $prod;
        }
        else{return NULL;}
    }

    public function getAll() {
        $pdo = FConnection::connect();
        $query = "SELECT * FROM products";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $prods = $stmt->fetchAll();
        $products = [];

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
      public function addToCart($productId, $cartId, $quantity) {
          $pdo = FConnection::connect();
          $query = 'insert into products_carts(`productId`, `cartId`, `quantity`) VALUES (:productId, :cartId, :quantity);';
          $stmt = $pdo->prepare($query);
          $stmt->execute(array(
              ':productId'=>$productId,
              ':cartId'=>$cartId,
              ':quantity'=>$quantity
          ));

      }

    function update($id, $product) {
        $pdo = FConnection::connect();
        $query = 'UPDATE products SET name = :name, description = :description, price = :price, imagePath = :image where id = :id;';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':name'=>$product->getName(),
            ':description'=>$product->getDescription(),
            ':price'=>$product->getPrice(),
            ':image'=>$product->getImagePath(),
            ':id'=>$id
        ));

    }

    function delete($id) {
        $pdo = FConnection::connect();
        $query = "DELETE FROM products where id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id'=>$id));
    }


}
