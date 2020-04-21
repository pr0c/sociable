<?php

namespace Vendor;

class DB {
    private static $host = 'localhost';
    private static $user;
    private static $password;
    private static $db_name;

    private static $connection = null;

    public static function run() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        self::$host = Core::$config->main['database']['host'];
        self::$user = Core::$config->main['database']['user'];
        self::$password = Core::$config->main['database']['password'];
        self::$db_name = Core::$config->main['database']['name'];

        if(is_null(self::$connection)) {
            self::$connection = mysqli_connect(self::$host, self::$user, self::$password, self::$db_name);

            if(!self::$connection) {
                trigger_error("Error establishing a database connection", E_USER_ERROR);
            }
            else {
                return self::$connection;
            }
        }
        else return self::$connection;
    }

    public static function getConnection() {
        if(is_null(self::$connection)) self::run();
        else return self::$connection;
    }
}