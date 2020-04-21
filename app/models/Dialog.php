<?php

namespace App\Models;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Model.php';

use Vendor\Model;
use App\Models\Message;

class Dialog extends Model {
    public function messages() {
        return $this->manyToMany(Dialog::class, Message::class, 'dialog_messages');
    }
}