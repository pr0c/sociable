<?php

namespace App\Controllers;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Controller.php';
include $_SERVER['DOCUMENT_ROOT'] . '/app/models/Dialog.php';
include $_SERVER['DOCUMENT_ROOT'] . '/app/models/Message.php';

use Vendor\Controller;
use Vendor\Request;
use App\Models\Message;
use App\Models\Dialog;

class MessageController extends Controller {
    public function index() {
        $dialog = Dialog::find(2);
        // $dialog->messages()->attach(new Message([
        //     'text' => 'new message attached to dialog',
        //     'author' => 1
        // ]));
        $dialog->with(['messages']);

        print_r($dialog->getFields());
    }
}