<?php

namespace Vendor;

class Controller {
    protected function render($view, $params = []) {
        $app = Core::$config->main['site'];

        $file = $_SERVER['DOCUMENT_ROOT'] . '/resources/views/' . $view;
        if(file_exists($file)) {
            $params['a'] = Core::$action;
            $params['c'] = Core::getControllerName();
            echo Core::$renderer->render($view, $params);
        }
        else {
            trigger_error("View doesn't exist: " . $file);
        }
    }
}