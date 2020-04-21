<?php

namespace Vendor;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/DB.php';
include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Request.php';
include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Router.php';
include $_SERVER['DOCUMENT_ROOT'] . '/app/Routes.php';
include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use App\Routes;
use Vendor\Config;
use Vendor\DB;
use Vendor\Request;
use Vendor\Router;

class Core {
    public static $config;
    public static $request;
    public static $db;
    public static $router;
    public static $routes;
    public static $renderer;
    public static $loader;
    public static $auth;

    public static $controller;
    public static $database;
    public static $action;

    private static $modelDir;

    public function run() {
        self::$modelDir = $_SERVER['DOCUMENT_ROOT'] . '/app/models/';

        self::$db = DB::run();

        if($_SERVER['REQUEST_METHOD'] == 'GET') $request = $_GET;
        else if($_SERVER['REQUEST_METHOD'] == 'POST') $request = $_POST;
        else $request = $_REQUEST;

        self::$request = new \Vendor\Request($request);
        self::$router = new \Vendor\Router(self::$request);
        self::$routes = new \App\Routes();
        self::$auth = new \Vendor\Auth();

        /** Twig */
        self::$loader = new \Twig\Loader\FilesystemLoader('resources/views');
        self::$renderer = new \Twig\Environment(self::$loader);
        self::$renderer->addGlobal('app', self::$config->main['site']);
        self::$renderer->addGlobal('auth', self::$auth);
        self::$renderer->addGlobal('router', self::$router);

        /** Running router */
        self::$routes->run();
        self::$router->resolveRoute();
    }

    public static function setConfig($alias, $config) {
        if(is_null(self::$config)) self::$config = new Config();
        self::$config->add($alias, $config);
    }

    public static function getControllerName() {
        $controller = explode('\\', get_class(self::$controller));
        $controllerName = str_replace('Controller', '', $controller[2]);

        return $controllerName;
    }

    public static function getModelFile($name) {
        $class = explode('\\', $name);
        $className = $class[count($class) - 1];
        $className[0] = strtoupper($className[0]);

        return self::$modelDir . $className . '.php';
    }
}