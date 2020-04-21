<?php

namespace Vendor;

class Router {
    private Request $request;
    private $routes = [];
    private $options = [];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    // public function get($url, $params = null) {
    //     $params_count = substr_count($url, '{');
    //     $this->routes[] = $url;
    //     $this->options[$params_count][] = count($this->routes)-1;
    // }

    public function route($alias, $controller = null, $action = null, $callback = null) {
        $this->routes[$alias] = [
            'controller' => $controller,
            'action' => $action,
            'callback' => $callback 
        ];
    }

    public function resolveRoute() {
        if($this->request->isExist('c')) {
            $controllerName = $this->request->c . 'Controller';
            $controllerName[0] = strtoupper($controllerName[0]);

            $controller = include($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/' . $controllerName . '.php');
            $controllerName = 'App\\Controllers\\' . $controllerName;
            Core::$controller = new $controllerName();

            if(!class_exists($controllerName, false)) {
                trigger_error("Action doesn't exist: " . $controllerName, E_USER_WARNING);
            } elseif($this->request->isExist('a')) {
                $action = $this->request->a;
                Core::$action = $action;

                if(method_exists(Core::$controller, $action)) {
                    $request = $this->request;
                    Core::$request = $request;
                    call_user_func([Core::$controller, $action], $this->request);
                } else {
                    trigger_error("Action doesn't exist: " . $controllerName . "::" . $action);
                }
            }
        } else {
            $action = explode('@', Core::$config->main['app']['default_action']);
            include($_SERVER['DOCUMENT_ROOT'] . '/app/controllers/' . $action[0] . '.php');
            $controllerName = 'App\\Controllers\\' . $action[0];
            Core::$controller = new $controllerName();
            Core::$action = $action[1];
            $request = $this->request;
            call_user_func([Core::$controller, Core::$action], $this->request);
        }
    }

    public function redirect($alias) {
        return header('Location: ' . $this->path($alias));
    }

    public function link($controller, $action) {

    }

    public function path($alias) {
        if(array_key_exists($alias, $this->routes)) {
            return sprintf("/?c=%s&a=%s", $this->routes[$alias]['controller'], $this->routes[$alias]['action']);
        }
    }
}