<?php

namespace Vendor;

use Vendor\Core;

class EntityBuilder {
    private $table;
    private $index;
    private $leftSubject;
    private $rightSubject;
    private $values = [];

    public function __construct($table, $index, $values, $subjectOneKey, $subjectTwoKey) {
        $this->table = $table;
        $this->index = $index;
        $this->leftSubject = $subjectOneKey;
        $this->rightSubject = $subjectTwoKey;
        $this->values = $values;
    }

    public function attach(Model $entity) {
        $entity->save();
        $entityFields = $entity->getFields();
        $query = sprintf("INSERT INTO `%s` (`%s`, `%s`) VALUES ('%s', '%s')",
            $this->table,
            $this->leftSubject,
            $this->rightSubject,
            $this->index,
            $entityFields[$entity->primaryKey]);
        $sendQuery = mysqli_query(Core::$db, $query);

        if(!$sendQuery) {
            trigger_error("Database error", E_USER_WARNING);
            return false;
        }
        else {
            $this->values = mysqli_fetch_all($sendQuery, MYSQLI_ASSOC);
            return $this;
        }
    }

    public function getFields() {
        return $this->values;
    }
}