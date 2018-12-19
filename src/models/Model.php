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
}
