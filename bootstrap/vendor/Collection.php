<?php

namespace Vendor;

use Iterator;

class Collection implements Iterator {
    private $collection = [];

    public function __construct(array $items) {
        $this->collection = $items;
    }

    public function first() {
        if(count($this->collection) > 0)
            return $this->collection[0];
        else return null;
    }

    public function get($index) {
        return $this->collection[$index];
    }

    public function rewind() {
        reset($this->collection);
    }

    public function current() {
        return current($this->collection);
    }

    public function key() {
        return key($this->collection);
    }

    public function next() {
        return next($this->collection);
    }

    public function valid() {
        $key = key($this->collection);
        
        return ($key !== NULL && $key !== FALSE);
    }
}