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
   * @param $method
   * @param $atgs
   */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

  /**
   * Get all the Rows
   *
   * @param array $order
   * @param int $limit
   */
    public function getAll(array $order = ['id', 'DESC'], $limit = null, $table = null)
    {
        return $this->orderBy($order[0], $order[1])->limit($limit)->fetchAll($table ? $table : $this->table);
    }

  /**
   * Get a Row
   *
   * @param string $value
   * @param string $coulmn
   */
    public function get($value, $coulmn = 'id')
    {
        return $this->where($coulmn . ' = ?', $value)->fetch($this->table);
    }

  /**
   * Check if row exists
   *
   * @param string $value
   * @param string $key
   */
    public function exists($value, $key = 'id')
    {
        return (bool) $this->select($key)->where($key . ' = ? ', $value)->fetch($this->table);
    }

  /**
   * Drop a row
   *
   * @param string $value
   * @param string $key
   */
    public function delete($id)
    {
        return $this->where('id = ?', $id)->delete($this->table);
    }

  /**
   * Join
   *
   * @param string $value
   * @param string $key
   */
    public function joinGetAll($select, $joins, $table = null)
    {
        return $this->db->select($select)->from($table ? $table : $this->table)->join($joins);
    }

  /**
   * Get a row after Joining
   *
   * @param string $select
   * @param string $table
   * @param string $joins
   * @param string $table
   */
    public function joinGetRow($select, $joins, $where, $table = null)
    {
        $table = $table ? $table : $this->table;
        return $this->db->select($select)->from($table)->join($joins)->where($table . '.id = ?', $where);
    }
}
