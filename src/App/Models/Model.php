<?php

namespace App\Models;

use PDO;
use Components\DBComponent;

class Model
{
    public $id;
    protected $table;
    private $query;
    private $connect;

    /**
     * Model constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->connect = new DBComponent();
        $this->connect = $this->connect->Connection();

        if (is_null($this->table)) {
            $table = explode('\\', get_class($this));
            $this->table = mb_strtolower(end($table)) . 's';
        }

        if ($params) {
            foreach ($params as $key => $value) {
                $this->$key = htmlspecialchars(trim($value));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $sql = "SELECT * FROM $this->table ORDER by id desc";

        return $this->connect->query($sql)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    /**
     * @param array $args
     * @return Model
     */
    public function select(array $args = ['*'])
    {
        $args = implode(', ', $args);

        $this->query = "SELECT $args FROM $this->table ";

        return $this;
    }

    /**
     * @param string $order
     * @param string $sort
     * @return Model
     */
    public function orderBy($order = 'id', $sort = 'desc')
    {
        $this->query .= "ORDER BY $order $sort";

        return $this;
    }

    /**
     * @param string $fetch
     * @return mixed
     */
    public function get($fetch = 'fetchAll')
    {
        $result = $this->connect->query($this->query)->$fetch(PDO::FETCH_CLASS, static::class);
        $this->query = '';
        return $result;
    }

    public function save()
    {
        $good = [];
        $value = '';
        $block = ['connect', 'query', 'table'];
        $properties = get_object_vars($this);

        $sql = "INSERT INTO $this->table (";

        foreach ($properties as $key => $value) {
            if (!is_null($value) && !in_array($key, $block, true)) {
                $good[$key] = $value;
                $sql .= " $key,";
            }
        }
        $sql = substr_replace($sql, ') VALUES (', -1);

        foreach ($good as $value) {
            $sql .= " '$value', ";
        }

        $sql = substr_replace($sql, ')', -2);
        $result = $this->connect->query($sql);

        if ($result) {
            return true;
        }

        return false;
    }
}