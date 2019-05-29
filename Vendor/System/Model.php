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
    
    public function all()
    {
        return $this->orderBy('id', 'DESC')->fetchAll($this->table);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

    public function get($id)
    {
        return $this->where('id = ?' , $id)->fetch($this->table);
    }

    public function exists($value, $key = 'id')
    {
        return (bool) $this->select($key)->where($key .'=?' , $value)->fetch($this->table);
    }

    public function delete($id)
    {
        return $this->where('id = ?' , $id)->delete($this->table);
    }
    
    public function hasOne($join, $id = null, $localId = null, $forginId = null)
    {
        $join = rtrim($join, 'Model');
 
        $file = $this->app->file->to( 'App/Models/' . $join . 'Model', '.php');
       
        $exists = $this->app->file->exists($file);
     
        if (! $exists) return $join . ' Not Found';
        
        $trace = debug_backtrace();

        $table = $trace[1]['object']->table;
            
        $join = $this->load->model($join)->table;
        
        return $this->db->select()->from($table)->join($join, $localId, $forginId)->where($table . '.id = ?', $id)->fetch();
    }

    public function hasMany($join, $id = null, $localId = null, $forginId = null)
    {
        $join = rtrim($join, 'Model');
 
        $file = $this->app->file->to( 'App/Models/' . $join . 'Model', '.php');
       
        $exists = $this->app->file->exists($file);
     
        if (! $exists) return $join . ' Not Found';
        
        $trace = debug_backtrace();

        $table = $trace[1]['object']->table;
            
        $join = $this->load->model($join)->table;

        return $this->db->select()->from($table)->join($join, $localId, $forginId)->where($table . '.id = ?', $id)->fetchAll();
    }
}