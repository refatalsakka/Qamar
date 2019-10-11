<?php

namespace System;

abstract class Model
{
  protected $app;

  protected $table;

  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  public function __get($key)
  {
    return $this->app->get($key);
  }

  public function __call($method, $args)
  {
    return call_user_func_array([$this->app->db, $method], $args);
  }

  public function all($limit = null)
  {
    return $this->orderBy('id', 'DESC')->limit($limit)->fetchAll($this->table);
  }

  public function allEnable($limit = null)
  {
    return $this->orderBy('id', 'DESC')->where('enable = ?', 1)->limit($limit)->fetchAll($this->table);
  }

  public function get($value, $coulmn = 'id')
  {
    return $this->where($coulmn . ' = ?', $value)->fetch($this->table);
  }

  public function getEnable($value, $coulmn = 'id')
  {
    return $this->where($coulmn . ' = ? and enable = ?', [$value, 1])->fetch($this->table);
  }

  public function selectTable($coulmn)
  {
    return $this->select($coulmn)->fetchAll($this->table);
  }

  public function exists($value, $key = 'id')
  {
    return (bool) $this->select($key)->where($key . ' = ? ', $value)->fetch($this->table);
  }

  public function delete($id)
  {
    return $this->where('id = ?', $id)->delete($this->table);
  }

  public function hasOne($select, $table, $joins, $where)
  {
    return $this->db->select($select)->from($table)->join($joins)->where($table . '.id = ?', $where)->fetch();
  }

  public function join($select, $table, $joins, $limit = null)
  {
    return $this->db->select($select)->from($table)->join($joins)->limit($limit)->fetchAll();
  }
}
