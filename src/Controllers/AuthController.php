<?php

namespace App\Controllers;

use App\Foundation\FCart;
use App\Foundation\FFavourites;
use App\Foundation\FRestaurant;
use App\Foundation\FCustomer;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Favourites;
use App\Views\VAuth;
use Validator;

class AuthController
{
    public function visualizeLogin()// il tasto login
    {
        $vauth = new VAuth();
        $vauth->visualizeLogin("");
    }

    public function visualizeSignUp()

    {
        $vauth = new VAuth();
        $vauth->visualizeSignUp("");
    }

    public function login()
    {
        $session = Session::getInstance();
        $email = $_POST["email"];
        $password = $_POST["password"];
        $fuser = new FCustomer();
        $frest = new FRestaurant();
        if ($fuser->authentication($email, $password)) {
            $user = $fuser->getByEmail($email);
            $session->saveUserInSession($user);
            redirect(url("home"));
        } else if ($frest->authentication($email, $password)) {
            $restaurant = $frest->getByEmail($email);
            $session->saveUserInSession($restaurant);
            redirect(url("admin"));
        } else {
            $message = "il login non è andato a buon fine";
            $vauth = new VAuth();
            $vauth->visualizeLogin($message);
        }

    }

    public function signUp()
    {

        $vauth = new VAuth();
        $isValid = validate($_POST, [

            "name" => ["maxLength:20", "minLength:3"], "surname" => ["minLength:2"], "email" => ["minLength:1"], "password" => ["minLength:5"]
        ]);
        if (!$isValid) {
            $message = "Il form non è valido";
            return $vauth->visualizeSignUp($message);
        }
        else {
            $name = $_POST["name"];
            $surname = $_POST["surname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $fuser = new FCustomer();
            $FCart = new FCart();
            $FFavourites = new FFavourites();
            
            $message = "";
            if (!$fuser->exist($email)) {
                $customer = new Customer();
                $customer->setName($name);
                $customer->setSurname($surname);
                $customer->setEmail($email);
                $customer->setPassword($password);
                $cart = new Cart();
                $cart->setId(NULL);
                $FCart->store($cart);

                $fav = new Favourites();
                $fav->setId(NULL);
                $FFavourites->store($fav);
                $customer->setCart($cart);
                $customer->setFav($fav);
                if (!$_FILES["uploadfile"]["name"]=="") {
                    $customer->setImagePath(uploadImage());
                }
                else {
                    $customer->setImagePath("/src/assets/images/user.jpg");
                }
                $fuser->store($customer);
                redirect("/login");
            } else {
                $message = "Esiste già un utente con questa e-mail";
                $vauth = new VAuth();
                return $vauth->visualizeSignUp($message);
            }

        }


    }

    public function logout()
    {
        $session = Session::getInstance();
        $session->logout();
        $vauth = new VAuth();
        $vauth->visualizeLogin("");
    }

    public function error404(){
        return setData("error-404", []); // metodo  di utility
    }
}