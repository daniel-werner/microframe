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

        $select = sprintf('SELECT `%s` FROM %s',
            $fields,
            $this->table);

        $where = '';
        $index = 0;
        foreach ($conditions as $key => $value) {
            if ($index > 0) {
                $where .= ' AND ';
            }
            $where .= sprintf('`%s` = %s',
                $this->db->real_escape_string($key),
                $this->db->real_escape_string($value));

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

    public function findBySql($sql)
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
        $sql = "SELECT * FROM ".$this->table;
        return $this->findBySql($sql);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM ".$this->table;
        $result_set = $this->db->query($sql);
        $row = $result_set->fetch_array();

        return array_shift($row);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM ".$this->table." ";
        $sql .= "WHERE id='".$this->db->real_escape_string($id)."'";
        $obj_array = $this->findBySql($sql);
        if (!empty($obj_array)) {
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

    public function firstBy($coulomb, $value)
    {
        $sql = "SELECT * FROM ".$this->table." ";
        $sql .= "WHERE ".$coulomb."='".$this->db->real_escape_string($value)."'";
        $obj_array = $this->findBySql($sql);
        if (!empty($obj_array)) {
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

    public function getBy($coulomb, $value)
    {
        $sql = "SELECT * FROM ".$this->table." ";
        $sql .= "WHERE ".$coulomb."='".$this->db->real_escape_string($value)."'";

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
        $attribute_pairs = [];
        foreach($attributes as $key => $value) {
          $attribute_pairs[] = "{$key}='{$value}'";
        }
    
        $sql = "UPDATE ".$this->table." SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE id='".$this->db->escape_string($this->attributes['id'])."' ";
        $sql .= "LIMIT 1";
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

        $sql = "DELETE FROM ".$this->table." ";
        $sql .= "WHERE id='".$this->db->escape_string($this->attributes['id'])."' ";
        $sql .= "LIMIT 1";
        $result = $this->db->query($sql);

        return $result;
    }
}
