<?php

namespace Microframe\Models;

use Microframe\Config\Database;
use PHPUnit\Runner\Exception;

abstract class Model
{
    protected $attributes = [];
    protected $fields = [];
    protected $table = '';
    private $db = '';
    public $errors = [];

    public function __construct($data = [])
    {
        $this->connect();

        foreach ($data as $field => $value) {
            if (in_array($field, $this->fields)) {
                $this->attributes[$field] = $value;
            }
        }
    }

    public function connect()
    {
        $this->db = new \mysqli(Database::$host, Database::$username, Database::$password, Database::$database);

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    public function buildSelectStatement($conditions)
    {
        $fields = implode('`,`', $this->fields);

        $select = sprintf(
            'SELECT `%s` FROM %s',
            $fields,
            $this->table
        );

        $where = '';
        $index = 0;
        foreach ($conditions as $key => $value) {
            if ($index > 0) {
                $where .= ' AND ';
            }
            $where .= sprintf(
                '`%s` = %s',
                $this->db->real_escape_string($key),
                $this->db->real_escape_string($value)
            );

            $index++;
        }

        if (!empty($conditions)) {
            $select .= sprintf(' WHERE %s', $where);
        }

        return $select;
    }

    public function buildInsertStatement()
    {
        $fields = [];
        $values = [];

        foreach ($this->attributes as $field => $value) {
            $fields[] = $this->db->real_escape_string($field);
            $values[] = $this->db->real_escape_string($value);
        }

        $fields = implode('`,`', $fields);
        $values = implode("','", $values);
        $insert = sprintf("INSERT INTO %s (`%s`) VALUES ('%s')", $this->table, $fields, $values);

        return $insert;
    }

    public function get($conditions)
    {
        $result = $this->db->query($this->buildSelectStatement($conditions));

        $results = [];

        if ($result) {
            while ($obj = $result->fetch_object()) {
                $item = [];
                foreach ($this->fields as $field) {
                    $item[$field] = $obj->{$field};
                }

                $class = get_class($this);
                $model = new \ReflectionClass($class);
                $results[] = $model->newInstance($item);
            }
        }
        $result->close();

        return $results;
    }

    public function save()
    {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }
        $result = $this->db->query($this->buildInsertStatement());
        if ($result) {
            $this->attributes['id'] = $this->db->insert_id;
        } else {
            $errors = [];
            foreach ($this->db->error_list as $error) {
                $errors[] = $error['error'];
            }
            printf("Save failed: %s\n", implode(', ', $errors));
            exit();
        }

        return $this;
    }

    public function __get($name)
    {
        $attribute = '';

        if (in_array($name, $this->fields)) {
            $attribute = $this->attributes[$name] ?? null;
        } else {
            throw new Exception('Attribute does not exists');
        }

        return $attribute;
    }

    protected function findBySql($sql)
    {
        $result = $this->db->query($sql);

        $results = [];

        if ($result) {
            while ($obj = $result->fetch_object()) {
                $item = [];
                foreach ($this->fields as $field) {
                    $item[$field] = $obj->{$field};
                }

                $class = get_class($this);
                $model = new \ReflectionClass($class);
                $results[] = $model->newInstance($item);
            }
        }
        $result->close();

        return $results;
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM '.$this->table;

        return $this->findBySql($sql);
    }

    public function countAll()
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->table;
        $resultSet = $this->db->query($sql);
        $row = $resultSet->fetch_array();

        return array_shift($row);
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM '.$this->table.' ';
        $sql .= "WHERE id='".$this->db->real_escape_string($id)."'";
        $objArray = $this->findBySql($sql);

        return $this->returnFirst($objArray);
    }

    public function firstBy($column, $value)
    {
        $sql = 'SELECT * FROM '.$this->table.' ';
        $sql .= 'WHERE '.$column."='".$this->db->real_escape_string($value)."'";
        $objArray = $this->findBySql($sql);

        return $this->returnFirst($objArray);
    }

    public function getBy($column, $value)
    {
        $sql = 'SELECT * FROM '.$this->table.' ';
        $sql .= 'WHERE '.$column."='".$this->db->real_escape_string($value)."'";

        return $this->findBySql($sql);
    }

    protected function validate()
    {
        $this->errors = [];

        // Add custom validations
        return $this->errors;
    }

    protected function sanitizedAttributes()
    {
        $sanitized = [];
        foreach ($this->attributes as $key => $value) {
            $sanitized[$key] = $this->db->escape_string($value);
        }

        return $sanitized;
    }

    protected function update()
    {
        $this->validate();
        if (!empty($this->errors)) {
            return false;
        }

        $attributes = $this->sanitizedAttributes();
        $attributePairs = [];
        foreach ($attributes as $key => $value) {
            $attributePairs[] = "{$key}='{$value}'";
        }

        $sql = 'UPDATE '.$this->table.' SET ';
        $sql .= join(', ', $attributePairs);
        $sql .= " WHERE id='".$this->db->escape_string($this->attributes['id'])."' ";
        $sql .= 'LIMIT 1';
        $result = $this->db->query($sql);

        return $result;
    }

    public function delete()
    {
        // After deleting, the instance of the object will still
        // exist, even though the database record does not.
        // This can be useful, as in:
        //   echo $user->first_name . " was deleted.";
        // but, for example, we can't call $user->update() after
        // calling $user->delete().

        $sql = 'DELETE FROM '.$this->table.' ';
        $sql .= "WHERE id='".$this->db->escape_string($this->attributes['id'])."' ";
        $sql .= 'LIMIT 1';
        $result = $this->db->query($sql);

        return $result;
    }

    public function deleteBy($column, $value)
    {
        $sql = 'DELETE FROM '.$this->table.' ';
        $sql .= 'WHERE '.$column."='".$this->db->escape_string($value)."'";
        $result = $this->db->query($sql);

        return $result;
    }

    //Eloquent like functions
    public function belongsTo($table, $foreignId = '')
    {
        if (!$foreignId) {
            $foreignId = $table.'_id';
        }
        if (!isset($this->attributes[$foreignId])) {
            return [];
        }
        $sql = 'SELECT * FROM '.$table.' ';
        $sql .= "WHERE id='".$this->db->escape_string($this->attributes[$foreignId])."'";
        $resultSet = $this->db->query($sql);
        $row = $resultSet->fetch_assoc();
        $resultSet->free_result();

        return $this->returnFirst($row);
    }

    public function hasOne($table, $foreignId = '')
    {
        if (!$foreignId) {
            $foreignId = $table.'_id';
        }
        $sql = 'SELECT * FROM '.$table.' ';
        $sql .= 'WHERE '.$this->db->escape_string($foreignId)."='".$this->db->escape_string($this->attributes['id'])."' ";
        $sql .= 'LIMIT 1';
        $resultSet = $this->db->query($sql);
        $row = $resultSet->fetch_assoc();
        $resultSet->free_result();

        return $this->returnFirst($row);
    }

    public function hasMany($table, $foreignId = '')
    {
        if (!$foreignId) {
            $foreignId = $table.'_id';
        }
        $sql = 'SELECT * FROM '.$table.' ';
        $sql .= 'WHERE '.$this->db->escape_string($foreignId)."='".$this->db->escape_string($this->attributes['id'])."'";
        $resultSet = $this->db->query($sql);
        $row = $resultSet->fetch_assoc();
        $resultSet->free_result();

        return $row;
    }

    protected function returnFirst($array)
    {
        if (!empty($array)) {
            return array_shift($array);
        } else {
            return [];
        }
    }
}
