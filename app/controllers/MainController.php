<?php

namespace App\Controllers;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Controller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/app/models/Comment.php';

use App\Models\Comment;
use Vendor\Controller;
use Vendor\Core;

class MainController extends Controller {
    public function index() {
        return $this->render('main/index.php.twig');
    }

    public function store() {
        $this->render('main/index.php.twig', ['user' => 'test']);
    }
}