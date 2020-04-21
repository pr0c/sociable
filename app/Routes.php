<?php

namespace App;

use Vendor\Core;

class Routes {
    private $router;

    public function __construct() {
        $this->router = Core::$router;
    }

    public function run() {
        $this->router->route('login', 'user', 'login');
        $this->router->route('register', 'user', 'register');
        $this->router->route('logout', 'user', 'logout');
        $this->router->route('news', 'main', 'index');
    }
}