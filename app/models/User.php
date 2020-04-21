<?php

namespace App\Models;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Model.php';
use Vendor\Model;

class User extends Model {

    public function getUser($id) {
        $this->find($id);
    }

    public function getUsers() {
        $this->getAll();
    }
}