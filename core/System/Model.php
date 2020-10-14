<?php

namespace System;

abstract class Model
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Table of a model
     *
     * @var $table
     */
    protected $table;

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Call shared Application Objects dynamically
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }

    /**
     * Call the methods from Database Object
     *
     * @property object $db
     * @param method $method
     * @param array $args
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

    /**
     * Get all the Rows
     *
     * @method orderBy
     * @method limit
     * @method fetchAll
     * @param array $order
     * @param int $limit
     * @param string $table
     */
    public function getAll(array $order = ['id', 'DESC'], int $limit = null, string $table = null)
    {
        return $this->orderBy($order[0], $order[1])->limit($limit)->fetchAll($table ? $table : $this->table);
    }

    /**
     * Get a Row
     *
     * @method where
     * @method fetch
     * @param string $value
     * @param string $coulmn
     */
    public function get(string $value, string $coulmn = 'id')
    {
        return $this->where($coulmn . ' = ?', $value)->fetch($this->table);
    }

    /**
     * Check if row exists
     *
     * @method select
     * @method where
     * @method fetch
     * @param string $value
     * @param string $key
     */
    public function exists(string $value, string $key = 'id')
    {
        return (bool) $this->select($key)->where($key . ' = ? ', $value)->fetch($this->table);
    }

    /**
     * Drop a row
     *
     * @method where
     * @method delete
     * @param string $id
     */
    public function delete(string $id)
    {
        return $this->where('id = ?', $id)->delete($this->table);
    }

    /**
     * Join
     *
     * @method select
     * @method from
     * @method join
     * @param string $select
     * @param string $joins
     * @param string $table
     */
    public function joinGetAll(string $select, string $joins, string $table = null)
    {
        return $this->db->select($select)->from($table ? $table : $this->table)->join($joins);
    }

    /**
     * Get a row after Joining
     *
     * @method select
     * @method from
     * @method join
     * @method where
     * @param string $select
     * @param string $table
     * @param string $joins
     * @param string $table
     */
    public function joinGetRow(string $select, string $joins, string $where, string $table = null)
    {
        $table = $table ? $table : $this->table;
        return $this->db->select($select)->from($table)->join($joins)->where($table . '.id = ?', $where);
    }
}
