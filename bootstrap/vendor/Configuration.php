<?php

namespace Vendor;

interface Configuration {
    public function __set($name, $value);
    public function __get($name);
    public function add($alias, $config);
}