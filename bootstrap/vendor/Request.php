<?php

namespace Vendor;

class Request {
    private $params;

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function __invoke() {
        if(isset($this->params['a'])) unset($this->params['a']);
        if(isset($this->params['c'])) unset($this->params['c']);

        return $this->params;
    }

    public function __get($name) {
        if($this->isExist($name)) return $this->params[$name];
        else return false;
    }

    public function getParams() {
        return $this->params;
    }

    public function isExist($name) {
        if(array_key_exists($name, $this->params)) return true;
        else return false;
    }
}