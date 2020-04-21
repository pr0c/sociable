<?php

namespace Vendor;

include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Collection.php';
include $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/EntityBuilder.php';

use Vendor\Collection;
use Vendor\EntityBuilder;

class Model {
    protected $table = null;
    public $primaryKey = 'id';
    private $values = [];

    public function __construct($values = []) {
        $this->table = $this->getTableName();
        if(count($values) > 0) $this->values = $values;
    }

    public function getFields() {
        return $this->values;
    }

    public function __get($name) {
        if(array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }
    }

    public function __set($name, $value) {
        $this->values[$name] = $value;
    }

    public function save() {
        $query = $this->prepareQuery();
        $sendQuery = mysqli_query(Core::$db, $query);
        if($sendQuery) {
            $this->id = mysqli_insert_id(Core::$db);
            return $this;
        }
        else return false;
    }

    public function getTable() {
        if(is_null($this->table)) return $this->getTableName();
        else return $this->table;
    }
    
    public static function find($id) {
        $object = self::getInstance();
        $query = mysqli_query(Core::$db, "SELECT * FROM `" . self::getTableName() . "` WHERE `" . $object->primaryKey . "` = ${id}");
        
        if(!$query) {
            trigger_error("Database error", E_USER_WARNING);
        }
        else {
            $values = mysqli_fetch_assoc($query);
            return new static((array) $values);
        }
        
        return null;
    }

    public static function where(array $params) {
        $query = sprintf("SELECT * from `%s` WHERE %s", self::getTableName(), self::prepareKeywords($params));
        $sendQuery = mysqli_query(Core::$db, $query);
        if(!$sendQuery) {
            trigger_error("Database error", E_USER_WARNING);
        }
        else {
            $items = mysqli_fetch_all($sendQuery, MYSQLI_ASSOC);
            $collection = [];
            foreach($items as $item) {
                $collection[] = new static((array) $item);
            }
            return new Collection($collection);
        }
    }

    protected function getAll() {
        $query = mysqli_query(Core::$db, "SELECT * from `" . $this->table . "`");
        if(!$query) {
            trigger_error("Database error", E_USER_WARNING);
        }
        else {
            $items = mysqli_fetch_all($query, MYSQLI_ASSOC);
            return new Collection($items);
        }

        return false;
    }

    protected function morphMany($morphTo, $morphBy, $table) {
        $index = $morphBy . '_id';
        $type = $morphBy . '_type';
        $id = $this->values['id'];
        $morphTo = str_replace('\\', '/', $morphTo);

        $query = mysqli_query(Core::$db, "SELECT * FROM `${table}` WHERE `${index}` = ${id} AND `${type}` = '${morphTo}'");

        if(!$query) {
            trigger_error("Database error", E_USER_WARNING);
        }
        else {
            return mysqli_fetch_assoc($query);
        }

        return false;
    }

    public function with(array $relations) {
        foreach($relations as $relation) {
            $this->values[$relation] = $this->$relation()->getFields();
        }

        return $this;
    }

    protected function manyToMany($subjectOne, $subjectTwo, $table = null, $subjectOneKey = null, $subjectTwoKey = null) {
        require_once Core::getModelFile($subjectOne);
        require_once Core::getModelFile($subjectTwo);

        $leftSubject = new $subjectOne();
        $rightSubject = new $subjectTwo();

        if(is_null($subjectOneKey)) $subjectOneKey = $leftSubject->getNameSingular() . '_id';
        if(is_null($subjectTwoKey)) $subjectTwoKey = $rightSubject->getNameSingular() . '_id';
        if(is_null($table)) $table = $leftTable . '_' . $rightTable;

        $query = sprintf("SELECT `%s`.* FROM `%s` INNER JOIN `%s` ON `%s`.%s = `%s`.%s WHERE `%s` = '%s'",
            $rightSubject->getTable(),
            $table,
            $rightSubject->getTable(),
            $rightSubject->getTable(),
            $rightSubject->primaryKey,
            $table,
            $subjectTwoKey,
            $subjectOneKey,
            $this->values[$this->primaryKey]);

        $sendQuery = mysqli_query(Core::$db, $query);
        if(!$sendQuery) {
            trigger_error("Database error", E_USER_WARNING);
        }
        else {
            $result = mysqli_fetch_all($sendQuery, MYSQLI_ASSOC);
            return new EntityBuilder($table, $this->values[$this->primaryKey], $result, $subjectOneKey, $subjectTwoKey);
        }
    }

    private static function getNameSingular() {
        $class_name = explode('\\', static::class);

        $class = $class_name[count($class_name) - 1];
        $class[0] = strtolower($class[0]);

        return $class;
    }

    private static function getTableName() {
        $class_name = explode('\\', static::class);

        $class = $class_name[count($class_name) - 1];
        $class[0] = strtolower($class[0]);
        $class = $class . "s";

        return $class;
    }

    private static function getInstance($attributes = []) {
        return new static((array) $attributes);
    }

    private function prepareQuery() {
        $fields = null;
        $values = null;

        if(isset($this->values[$this->primaryKey])) {
            foreach($this->values as $key => $value) {
                if($fields == null) $fields = '`' . $key . '` = \'' . $value . '\'';
                else $fields .= ', `' . $key . '` = \'' . $value . '\''; 
            }

            $query = sprintf("UPDATE `%s` SET %s WHERE `%s` = '%s'", $this->table, $fields, $this->primaryKey, $this->values[$this->primaryKey]);
        }
        else {
            foreach($this->values as $key => $value) {
                if($fields == null) {
                    $fields = '`' . $key . '`';
                    $values = '\'' . $value . '\'';
                }
                else {
                    $fields .= ', `' . $key . '`';
                    $values .= ', \'' . $value . '\'';
                }
                
            }

            $query = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $this->table, $fields, $values);
        }

        return $query;
    }

    private static function prepareKeywords($fields) {
        $keys = null;
        foreach($fields as $key => $value) {
            if($keys == null) $keys = '`' . $key . '` = \'' . $value . '\'';
            else $keys .= ' AND `' . $key . '` = \'' . $value . '\''; 
        }

        return $keys;
    }
}