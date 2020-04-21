<?php

namespace App\Models;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Model.php';
use Vendor\Model;

class Comment extends Model {
    public function getComment($id) {
        $this->find($id);
    }

    public function getComments() {
        return $this->getAll();
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commented', 'commented');
    }
}