<?php
require_once __DIR__ . "/MysqliDb.php";

class Entity
{
    public static $relations;
    public $ID;

    /**
     * @param array $data
     * @param array $updateColumns
     * @throws Exception
     */
    public static function insertMultiple(array $data, array $updateColumns = []) {
        $db = MysqliDb::getInstance();
        $tableName = get_called_class();
        $ids = $db->insertMulti($tableName, $data, $updateColumns);
        if ($ids === false) {
            throw new Exception($db->getLastError());
        }
    }

    /**
     * @return int
     */
    public static function getTotalPages()
    {
        return MysqliDb::getInstance()->totalPages;
    }

    /**
     * @return int
     */
    public static function getTotalCount()
    {
        return MysqliDb::getInstance()->totalCount;
    }

    /**
     * @param int $count
     */
    public static function setPageLimit($count)
    {
        MysqliDb::getInstance()->pageLimit = $count;
    }

    /**
     * @param string $whereProp
     * @param string $whereValue
     * @param string $operator
     * @return $this
     */
    static function orWhere($whereProp, $whereValue = 'DBNULL', $operator = '=')
    {
        return self::where($whereProp, $whereValue, $operator, 'OR');
    }

    /**
     * @param string $whereProp
     * @param string $whereValue
     * @param string $operator
     * @param string $cond
     * @return $this
     */
    static function where($whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        MysqliDb::getInstance()->where($whereProp, $whereValue, $operator, $cond);
        return new static;
    }

    /**
     * @param string $orderByField
     * @param string $orderByDirection
     * @param null $customFieldsOrRegExp
     * @return static
     * @throws Exception
     */
    static function orderBy($orderByField, $orderByDirection = "DESC", $customFieldsOrRegExp = null)
    {
        MysqliDb::getInstance()->orderBy($orderByField, $orderByDirection, $customFieldsOrRegExp);
        return new static;
    }

    /**
     * @param bool $getRelated
     * @param int $page
     * @return $this[]
     * @throws Exception
     */
    static function read_all($getRelated = true, $page = 0)
    {
        $db = MysqliDb::getInstance();
        $classObjects = array();
        $tableName = get_called_class();
        $r = new ReflectionClass($tableName);
        $object = $r->newInstanceArgs();
        $cols = self::getRelatedObjects($object, $getRelated, false);
        if ($getRelated) {
            self::getJoins($tableName);
        }
        if (empty($page)) {
            $results = $db->withTotalCount()->get($tableName, null, $cols);
        } else {
            $results = $db->withTotalCount()->paginate($tableName, $page, $cols);
        }
        if (empty($db->getLastErrno())) {
            if (!empty($results)) {
                foreach ($results as $row) {
                    $objInstance = $r->newInstanceArgs();
                    $objInstance->fillObjects($row);
                    array_push($classObjects, $objInstance);
                }
            }
        } else {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
        return $classObjects;
    }

    /**
     * @throws Exception
     */
    static function delete_all()
    {
        $db = MysqliDb::getInstance();
        $tableName = get_called_class();
        $result = $db->delete($tableName);
        if (!$result) {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }

    /**
     * @param array $data
     * @param bool $useJoins
     * @throws Exception
     */
    static function update_all(array $data, bool $useJoins = false)
    {
        $db = MysqliDb::getInstance();
        $tableName = get_called_class();
        if ($useJoins) {
            self::getJoins($tableName);
        }
        $result = $db->update($tableName, $data);
        if (!$result) {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    static function count()
    {
        $db = MysqliDb::getInstance();
        $tableName = get_called_class();
        self::getJoins($tableName);
        $count = $db->getValue($tableName, "count(*)");
        if (empty($db->getLastErrno())) {
            if (isset($count)) {
                return $count;
            }
            return 0;
        } else {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }

    /**
     * @param string $field
     * @return int
     * @throws Exception
     */
    static function sum($field)
    {
        $db = MysqliDb::getInstance();
        $tableName = get_called_class();
        self::getJoins($tableName);
        $result = $db->getValue($tableName, "sum({$field})");
        if (empty($db->getLastErrno())) {
            if (isset($result)) {
                return $result;
            }
            return 0;
        } else {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }

    /**
     * @param bool $nullCheck
     * @throws Exception
     */
    function save($nullCheck = true, $updateColumns = [])
    {
        $db = MysqliDb::getInstance();
        $tableName = get_class($this);
        $data = array();
        $useTransaction = $db->startTransaction();
        $props = get_object_vars($this);
        foreach ($props as $prop => $prop_value) {
            if ($prop != "ID" && !$prop_value instanceof Entity) {
                if ($nullCheck) {
                    if (isset($prop_value)) {
                        $data[$prop] = $prop_value;
                    }
                } else {
                    $data[$prop] = $prop_value;
                }
            }
        }
        if (count($data) > 0) {
            $success = false;
            if (empty($this->ID)) {
                if ($updateColumns) {
                    $db->onDuplicate($updateColumns);
                }
                $id = $db->insert($tableName, $data);
                if ($id) {
                    $success = true;
                    $this->ID = $id;
                }
            } else {
                $db->where('ID', $this->getID());
                $success = $db->update($tableName, $data);
            }

            if ($useTransaction) {
                if ($success) {
                    $db->commit();
                } else {
                    $db->rollback();
                }
            }
            if (!$success) {
                throw new Exception($db->getLastError(), $db->getLastErrno());
            }
        }
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @param bool $getRelated
     * @return $this|bool
     * @throws Exception
     */
    function read($getRelated = true)
    {
        $db = MysqliDb::getInstance();
        $tableName = get_class($this);
        $cols = $this->getRelatedObjects($this, $getRelated);
        if ($getRelated) {
            $this->getJoins($tableName);
        }
        $result = $db->getOne($tableName, $cols);
        if (empty($db->getLastErrno())) {
            if (empty($result)) {
                return false;
            } else {
                $this->fillObjects($result);
                return $this;
            }
        } else {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }

    /**
     * @param object $object
     * @param bool $getRelated
     * @param bool $includeWhere
     * @return array
     */
    private static function getRelatedObjects($object, $getRelated, $includeWhere = true)
    {
        $cols = array();
        $db = MysqliDb::getInstance();
        $props = get_object_vars($object);
        $className = get_class($object);
        foreach ($props as $prop => $prop_value) {
            if (isset($prop_value)) {
                if ($prop_value instanceof Entity) {
                    if ($getRelated) {
                        $cols = array_merge($cols, self::getRelatedObjects($prop_value, $getRelated, $includeWhere));
                    }
                } else if ($includeWhere) {
                    $db->where($className . '.' . $prop, $prop_value);
                }
            }
            if (!$prop_value instanceof Entity) {
                array_push($cols, $className . '.' . $prop . ' as ' . $className . $prop);
            }
        }
        return $cols;
    }

    /**
     * @param string $className
     * @throws Exception
     */
    private static function getJoins($className)
    {
        $props = get_class_vars($className);
        $db = MysqliDb::getInstance();
        if (isset($props["relations"])) {
            foreach ($props["relations"] as $joinTable => $joinCondition) {
                $db->join($joinTable, $className . '.' . $joinCondition[1] . '=' . $joinTable . '.' . $joinCondition[0]);
                static::getJoins($joinTable);
            }
        }
    }

    /**
     * @param array $data
     */
    public function fillObjects($data)
    {
        $props = get_object_vars($this);
        $className = get_class($this);
        foreach ($props as $prop => $prop_value) {
            if ($prop_value instanceof Entity) {
                $prop_value->fillObjects($data);
            } else {
                if (isset($data[$className . $prop])) {
                    $this->{$prop} = $data[$className . $prop];
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    function delete()
    {
        $db = MysqliDb::getInstance();
        $tableName = get_class($this);
        $db->where('ID', $this->getID());
        $result = $db->delete($tableName);
        if (!$result) {
            throw new Exception($db->getLastError(), $db->getLastErrno());
        }
    }
}