<?php

namespace Vendor;

class Auth {
    public $user;
    private $id;

    public function __construct() {
        $this->user = null;
        $this->run();
    }

    public function run() {
        if($this->isLogged()) {
            $this->id = $_COOKIE['user_id'];
        }
    }

    public function auth(Model $user) {
        $this->user = $user;
        setcookie('auth', 'true', time() + 36000);
        setcookie('user_id', $user->id, time() + 36000);
    }

    public function logout() {
        if(isset($_COOKIE['auth'])) {
            $this->user = null;
            unset($_COOKIE['auth']);
            unset($_COOKIE['user_id']);
            setcookie('auth', null, -1);
            setcookie('user_id', null, -1);
        }
    }

    public function isLogged() {
        return isset($_COOKIE['auth']);
    }

    public function getId() {
        return $this->id;
    }
}