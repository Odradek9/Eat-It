<?php

namespace App\Controllers;

class Session
{
    private static $instance;

    private function __construct() {}

    public static function getInstance(): Session{
        if (self::$instance == null) {
            self::$instance = new Session();
        }
        return self::$instance;

    }

    private function newSession(){ //to check if a session is already started and active.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function logout(): bool {
        if (isset($_COOKIE["PHPSESSID"])) {
            $this->newSession();
            session_unset();
            session_destroy();
            setcookie("PHPSESSID", "", time() - 3600, "/");
            $bool = true;
        }
        return $bool;
    }

    public function isUserLogged(): bool {
        $this->newSession();
        return (isset($_COOKIE["PHPSESSID"]) && (isset($_SESSION["customer"])));

    }


    public function isRestaurantLogged(): bool {
        $this->newSession();
        return (isset($_COOKIE["PHPSESSID"]) && isset($_SESSION["restaurant"]));
    }

    public function loadUser() {

        $this->newSession();

        if(isset($_SESSION["customer"])){
            $user =  $_SESSION["customer"];
            return unserialize($user);

        } else if (isset($_SESSION["restaurant"])) {
            $restaurant =  $_SESSION["restaurant"];
            return unserialize($restaurant);
        }
    }

    public function saveUserInSession($user) {
    $this->newSession();

    session_regenerate_id(true);

    $userSer = serialize($user);

    if ( get_class($user) == 'App\Models\Customer') {
        $_SESSION['customer'] = $userSer;

    } else if ( get_class($user) == 'App\Models\Restaurant' ) {
        $_SESSION['restaurant'] = $userSer;


    }
    }

    public function loadCart() {
        $this->newSession();
        $user = unserialize($_SESSION["customer"]);
        return $user->getCart();
    }

}
