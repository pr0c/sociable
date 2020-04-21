<?php

namespace Vendor;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Configuration.php';
use Vendor\Configuration;

class Config implements Configuration {
    private $cfg = null;

    public function __construct($config = null) {
        if(!is_null($config))
            $this->cfg = include($config);
    }

    public function __set($name, $value) {
        if(array_key_exists($name, $this->cfg)) {
            $this->cfg[$name] = $value;
            return true;
        }
        else return false;
    }

    public function __get($name) {
        if(array_key_exists($name, $this->cfg)) {
            return $this->cfg[$name];
        }
        else return false;
    }

    public function add($alias, $config) {
        $this->cfg[$alias] = include($config);
    }

    public function getConfig() {
        return $this->cfg;
    }
}